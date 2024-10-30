<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Single Marketplace features
 */

class Juno_Dokan extends WC_Juno_Marketplace_Rules {
  public function get_vendors_from_order( $order ) {
    $this->log( sprintf( 'Recuperando vendedores por pedido: #%s', $order->get_id() ) );

    $order_id      = $order->get_id();
    $suborder_ids  = array();
    $order_vendors = array();
    $sellers       = dokan_get_sellers_by( $order_id );

    $this->log( sprintf( 'Há %s vendedores no pedido #%s.', count( $sellers ), $order_id ) );

    if ( count( $sellers ) === 1 ) {
      $suborder_ids[] = $order_id;
    } else {
      $dokan_ids    = dokan_get_suborder_ids_by( $order_id );
      $suborder_ids = wp_list_pluck( $dokan_ids, 'ID' );
    }

    foreach ( $suborder_ids as $suborder_id ) {
      $vendor_id = dokan_get_seller_id_by_order( $suborder_id );
      $token     = $this->get_vendor_token( $vendor_id );
      $total     = dokan()->commission->get_earning_by_order( $suborder_id, 'seller' );

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
      // adicionar um log dizendo que cada vendedor recebeu X
    }

    $this->log( 'Vendedores do pedido #' . $order->get_id() . ': ' . print_r( $order_vendors, true ) );

    return $order_vendors;
  }
}
