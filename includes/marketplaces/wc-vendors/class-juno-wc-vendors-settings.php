<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Single Marketplace settings
 */
class Juno_WC_Vendors_Settings extends WC_Juno_Marketplace_Settings {
  function hooks() {
    add_action( 'woocommerce_order_status_processing', array( $this, 'order_paid' ), 10, 2 );

    add_action( 'wcvendors_settings_before_paypal', array( $this, 'juno_settings' ) );

    add_action( 'admin_init', array( $this, 'save_settings' ) );
  }

  public function order_paid( $order_id, $order ) {
    if ( apply_filters( 'wc_juno_marketplace_update_commission', ! empty( $order->get_meta( '_juno_split_params' ) ) ) ) {
      WCV_Commission::set_order_commission_paid( $order_id );
    }
  }


  public function juno_settings() {
    $store_id  = get_current_user_id();
    $token     = get_user_meta( $store_id, '_juno_token', true );
    ?>
    <div class="_juno_token_container">
      <p><b><?php _e( 'Token Juno', 'juno-split' ) ?></b><br />
        <input type="text" name="_juno_token" id="_juno_token" placeholder="<?php _e( 'Seu token Juno', 'juno-split' ); ?>" value="<?php echo $token; ?>" />
        <br />
        <?php echo wc_juno_marketplace_token_message( $token ); ?>
      </p>
    </div>

    <hr />
    <?php
  }

  public function save_settings() {
    if ( isset( $_POST['wc-vendors-nonce'] ) ) {
      if ( ! wp_verify_nonce( $_POST['wc-vendors-nonce'], 'save-shop-settings-admin' ) ) {
        return false;
      }

      $store_id = get_current_user_id();

      if ( array_key_exists( '_juno_token', $_POST ) ) {
        update_user_meta( $store_id, '_juno_token', esc_attr( $_POST['_juno_token'] ) );
      }
    }
  }
}

new Juno_WC_Vendors_Settings();
