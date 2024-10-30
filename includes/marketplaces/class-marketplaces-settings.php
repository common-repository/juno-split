<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

use WC_Juno\functions as h;

/**
 * Single Marketplace settings
 */
class WC_Juno_Marketplace_Settings {
  function __construct() {
    $this->hooks();
    $this->global_features();
  }

  public function hooks() {}


  public function global_features() {
    $this->profile_hooks();
  }


  public function profile_hooks() {
    add_action( 'edit_user_profile', array( $this, 'vendor_profile' ) );
    add_action( 'show_user_profile', array( $this, 'vendor_profile' ) );

    add_action( 'personal_options_update', array( $this, 'save_vendor_profile' ) );
    add_action( 'edit_user_profile_update', array( $this, 'save_vendor_profile' ) );
  }


  public function vendor_profile( $vendor ) {
    include_once 'views/html-edit-profile.php';
  }


  public function save_vendor_profile( $vendor_id ) {
    if ( ! current_user_can( 'edit_user', $vendor_id ) ) {
      return false;
    }

    if ( isset( $_POST['_juno_token'] ) ) {
      update_user_meta( $vendor_id, '_juno_token', esc_attr( $_POST['_juno_token'] ) );
    }
  }
}

new WC_Juno_Marketplace_Settings();
