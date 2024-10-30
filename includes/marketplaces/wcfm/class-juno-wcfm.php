<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Single Marketplace features
 */

class Juno_WCFM extends WC_Juno_Marketplace_Rules {
  public function get_vendors_from_order( $order ) {
    $this->log( sprintf( 'Recuperando vendedores por pedido: #%s', $order->get_id() ) );
    $order_id  = $order->get_id();
    $vendors   = array();

    if ( function_exists( 'wcfm_get_vendor_store_by_post' ) ) {
      $items = $order->get_items( 'line_item' );

      foreach( $items as $item ) {
        $line_item = new \WC_Order_Item_Product( $item );
        $product_id = $line_item->get_product_id();
        $vendor_id  = wcfm_get_vendor_id_by_post( $product_id );

        if( !$vendor_id ) continue;
        if( in_array( $vendor_id, $vendors ) ) continue;

        $vendors[] = $vendor_id;
      }

    } else {
      $this->log( sprintf( 'Pedido #%s: não encontrada função wcfm_get_vendor_store_by_post', $order_id ) );
      return [];
    }

    $vendors = array_unique( $vendors );

    $this->log( sprintf( 'Há %s vendedores no pedido #%s.', count( $vendors ), $order_id ) );

    // nenhum vendedor
    if ( count( $vendors ) === 0 ) {
      return [];
    }

    $sellers  = array();

    foreach ( $vendors as $vendor_id ) {
      $token     = $this->get_vendor_token( $vendor_id );

      if ( ! $token ) {
        $this->log( sprintf( 'Token não definido para o vendedor #%s', $vendor_id ) );

        $order->add_order_note( sprintf( 'Conta inválida #%s: Token Juno não definido', $vendor_id ) );
        continue;
      }

      global $WCFMmp;

      $commission = $WCFMmp->wcfmmp_commission->wcfmmp_calculate_vendor_order_commission( $vendor_id, $order_id, $order );

      if ( ! $commission['commission_amount'] ) {
        $this->log( sprintf( 'Comissão inválida para o vendedor #%s: %s', $vendor_id, print_r( $commission, true ) ) );
        continue;
      }

      $order->add_order_note( sprintf( 'Split de %s iniciado para o vendedor #%s', wc_price( $commission['commission_amount'] ), $vendor_id ) );

      $sellers[] = array(
        'vendor_id' => $vendor_id,
        'token'     => $token,
        'total'     => wc_format_decimal( $commission['commission_amount'], 2, false ),
      );
      // adicionar um log dizendo que cada vendedor recebeu X
    }

    return $sellers;
  }
}
