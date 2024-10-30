<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
use WC_Juno\functions as h;

/**
 * Process Payments
 */
class WC_Juno_Marketplace_Split {
  public $log_enabled = 'no';

  function __construct() {
    add_action( 'woo_juno_payment_bank_slip_api_params', array( $this, 'juno_split' ), 1, 2 );
    add_action( 'woo_juno_payment_credit_card_api_params', array( $this, 'juno_split' ), 1, 2 );
  }


  public function juno_split( $params, $order ) {
    $this->log_enabled = h\get_settings_option( 'split_log', 'no' );

    $api = new \WC_Juno\Service\Juno_REST_API();
    if ( ! isset( $api->version ) || 2 !== $api->version ) {
      $this->log( 'Não possível processar o split no pedido #' . $order->get_id() . ': versão da API inválida.' );

      return $params;
    }

    $class = $this->get_marketplace();
    if ( ! $class ) {
      $this->log( 'Não possível processar o split no pedido #' . $order->get_id() . ': classse de marketplace não encontrada.' );

      return $params;
    }

    $vendors     = $class->get_vendors_from_order( $order );
    $split       = array();
    $final_total = wc_format_decimal( $params['charge']['amount'] * $params['charge']['installments'], 2, false ); // including fees
    $split_total = 0;

    // prevent breakage of the sum in installments
    if ( $params['charge']['installments'] > 1 ) {
      $params['charge']['totalAmount'] = $final_total;
      unset( $params['charge']['amount'] );
    }

    foreach ( $vendors as $vendor ) {
      $split[] = apply_filters( 'wc_juno_marketplace_vendor_split', array(
        'recipientToken'  => $vendor['token'],
        'amount'          => $vendor['total'],
        'amountRemainder' => false,
        'chargeFee'       => $class->charge_vendor_fees( $vendor['vendor_id'], $order )
      ), $order, $api );

      $split_total += $vendor['total'];
    }

    $split = array_filter( $split );

    if ( $split ) {
      // add admin split
      $admin_amount = $final_total - $split_total;

      if ( 0 < $admin_amount ) {
        $split[] = array(
          'recipientToken'  => $class->get_token(),
          'amount'          => wc_format_decimal( $admin_amount, 2 ),
          'amountRemainder' => true,
          'chargeFee'       => true  // always charge admin fee
        );
      } else {
        // Edge case: admin has no commission
        // let's give to first vendor the mandatory parameter
        $split[0]['amountRemainder'] = true;
      }

      $params['charge']['split'] = $split;

      // save split settings in case we need again
      $order->update_meta_data( '_juno_split_params', $split );
      $order->save();

      $this->log( 'Parâmetros do pedido #' . $order->get_id() . ':' . print_r( $params, true ) );
    } else {
      $this->log( 'Nenhum vendedor válido no pedido #' . $order->get_id() );
    }

    return $params;
  }


  public function log( $message ) {
    if ( 'yes' === $this->log_enabled ) {
      $log = new WC_Logger();
      $log->add( 'juno-split', $message );
    }
  }


  public function get_marketplace() {
    $class = WC_Juno_Marketplace::get_marketplace_class();

    if ( class_exists( $class ) ) {
      return new $class();
    }

    return false;
  }
}

new WC_Juno_Marketplace_Split();
