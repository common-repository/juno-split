<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Single Marketplace settings
 */
class Juno_WCFM_Settings extends WC_Juno_Marketplace_Settings {
  function hooks() {
    add_action( 'wcfm_marketplace_settings_fields_billing', array( $this, 'add_juno_settings' ), 10, 2 );
    add_action( 'update_user_metadata', array( $this, 'save_juno_token' ), 50, 4 );
  }

  public function add_juno_settings( $settings, $user_id ) {
    $token = get_user_meta( $user_id, '_juno_token', true );
    $settings['_juno_token'] = array(
      'label'         => __( 'Token Juno', 'juno-split' ),
      'name'          => 'vendor_data[_juno_token]',
      'type'          => 'text',
      'in_table'      => 'yes',
      'wrapper_class' => 'paymode_field paymode_juno',
      'class'         => 'wcfm-text wcfm_ele',
      'label_class'   => 'wcfm_title wcfm_ele',
      'value'         => $token
    );

    return $settings;
  }

  public function save_juno_token( $default, $user_id, $meta_key, $value ) {
    if ( 'wcfmmp_profile_settings' === $meta_key && isset( $value['vendor_data']['_juno_token'] ) ) {
      update_user_meta( $user_id, '_juno_token', $value['vendor_data']['_juno_token'] );
    }

    return $default;
  }
}

new Juno_WCFM_Settings();
