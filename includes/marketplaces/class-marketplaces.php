<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

use WC_Juno\functions as h;

/**
 * Single Marketplace features
 */
abstract class WC_Juno_Marketplace_Rules {
  public $log_enabled = 'no';

  function __construct() {
    $this->log_enabled = h\get_settings_option( 'split_log', 'no' );
    $this->vendor_fees = 'yes' === h\get_settings_option( 'charge_fee', 'no' );
  }

  abstract public function get_vendors_from_order( $order );


  public function log( $message ) {
    if ( 'yes' === $this->log_enabled ) {
      $log = new WC_Logger();
      $log->add( 'juno-split', $message );
    }
  }


  public function charge_vendor_fees( $vendor_id, $order ) {
    return apply_filters( 'wc_juno_marketplace_vendor_fees', $this->vendor_fees, $vendor_id, $order );
  }


  public function get_token() {
    return h\get_private_token();
  }

  public function get_vendor_token( $vendor_id ) {
    return get_user_meta( intval( $vendor_id ), '_juno_token', true );
  }
}
