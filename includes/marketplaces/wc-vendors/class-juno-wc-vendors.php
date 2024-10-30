<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Single Marketplace features
 */

class Juno_WC_Vendors extends WC_Juno_Marketplace_Rules {
  public function get_vendors_from_order( $order ) {
    $this->log( sprintf( 'Recuperando vendedores por pedido: #%s', $order->get_id() ) );

    $order_id      = $order->get_id();

    // gerar comissões
    WCV_Commission::log_commission_due( $order_id );

    $order_vendors = array();
    $sellers       = WCV_Vendors::get_vendor_dues_from_order( $order );

    $this->log( sprintf( 'Há %s vendedores no pedido #%s.', count( $sellers ), $order_id ) );

    foreach ( $sellers as $seller ) {
      $vendor_id = $seller['vendor_id'];
      $total     = isset( $seller['total'] ) ? $seller['total'] : 0;

      if ( ! WCV_Vendors::is_vendor( $vendor_id ) ) {
        continue;
      }

      if ( ! $total ) {
        $order->add_order_note( sprintf( 'Comissão inválida: não definido o total para o vendedor #%s', $vendor_id ) );

        $this->log( sprintf( 'Sem total disponível para vendedor #%s: %s', $vendor_id, print_r( $seller, true ) ) );
        continue;
      }

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

    return $order_vendors;
  }
}
