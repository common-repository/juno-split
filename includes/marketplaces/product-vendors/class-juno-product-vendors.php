<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Single Marketplace features
 */

class Juno_Product_Vendors extends WC_Juno_Marketplace_Rules {
  public function get_vendors_from_order( $order ) {
    $this->log( sprintf( 'Recuperando vendedores por pedido: #%s', $order->get_id() ) );

    // criar comissões
    do_action( 'woocommerce_order_action_wcpv_manual_create_commission', $order );

    $order_id      = $order->get_id();
    $order_vendors = array();
    $sellers       = $this->get_commission_by_order_id( $order_id );

    if ( ! $sellers ) {
      $this->log( 'Nenhuma comissão encontrada no pedido #' . $order_id );

      return array();
    }

    $this->log( sprintf( 'Há %s vendedores no pedido #%s.', count( $sellers ), $order_id ) );

    foreach ( $sellers as $vendor_id => $total ) {
      $token = $this->get_vendor_token( $vendor_id );

      if ( empty( $token ) ) {
        $order->add_order_note( sprintf( 'Conta inválida #%s: Token Juno não definido', $vendor_id ) );

        $this->log( sprintf( 'Token não definido para o vendedor #%s', $vendor_id ) );

        continue;
      }

      $order->add_order_note( sprintf( 'Split de %s iniciado para o vendedor #%s', wc_price( $total ), $vendor_id ) );

      $order_vendors[] = array(
        'vendor_id' => $vendor_id,
        'token'     => $token,
        'total'     => wc_format_decimal( $total, 2, false ),
      );
    }

    $this->log( 'Vendedores do pedido #' . $order->get_id() . ': ' . print_r( $order_vendors, true ) );

    return $order_vendors;
  }


  public function get_commission_by_order_id( $order_id = null, $all_fields = false ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'wcpv_commissions';

    if ( null === $order_id ) {
      return false;
    }

    $commission_status = array( 'unpaid', 'void' );
    $commissions       = $wpdb->get_results(
      "SELECT *
      FROM $table_name
      WHERE order_id = $order_id
      AND commission_status IN ( '" . implode( "','", array_map( 'esc_sql', $commission_status ) ) . "' )"
      // phpcs:enable
    );

    if ( ! empty( $commissions ) ) {
      $vendors = array();

      if ( $all_fields ) {
        return $commissions;
      }

      foreach ( $commissions as $commission ) {
        $vendor_id = $commission->vendor_id;
        if ( ! isset( $vendors[ $vendor_id ] ) ) {
          $vendors[ $vendor_id ] = 0;
        }

        $vendors[ $vendor_id ] += $commission->total_commission_amount;
      }

      return $vendors;
    }

    return array();
  }


  public function get_vendor_token( $vendor_id ) {
    $data = get_term_meta( $vendor_id, 'vendor_data', true );

    if ( isset( $data['juno_token'] ) ) {
      return $data['juno_token'];
    }

    return '';
  }


  /**
   * Updates the paid status for a commission record
   *
   * @access public
   * @since 2.0.0
   * @version 2.0.0
   * @param int $order_id
   * @param int $order_item_id
   * @param string $commission_status
   * @return bool
   */
  public function update_order_commissions( $commission_id = 0, $order_item_id ) {
    global $wpdb;

    $commission_status = 'paid';
    $table_name        = $wpdb->prefix . 'wcpv_commissions';

    $sql = "UPDATE {$table_name}";
    $sql .= " SET `commission_status` = %s,";

    $sql .= " `paid_date` = %s";
    $date = date( 'Y-m-d H:i:s' );

    $sql .= " WHERE `id` = %d";
    $sql .= " AND `commission_status` != %s";

    // updates the commission table
    $wpdb->query( $wpdb->prepare( $sql, $commission_status, $date, (int) $commission_id, $commission_status ) );

    // also update the order item meta to leave a trail
    $sql = "UPDATE {$wpdb->prefix}woocommerce_order_itemmeta";
    $sql .= " SET `meta_value` = %s";
    $sql .= " WHERE `order_item_id` = %d";
    $sql .= " AND `meta_key` = %s";

    $status = $wpdb->get_var( $wpdb->prepare( $sql, $commission_status, $order_item_id, '_commission_status' ) );

    do_action( 'wcpv_commissions_updated', $commission_id, $order_item_id );

    return true;
  }
}
