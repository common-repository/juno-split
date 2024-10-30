<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Single Marketplace settings
 */
class Juno_Dokan_Settings extends WC_Juno_Marketplace_Settings {
  function hooks() {
    add_action( 'dokan_withdraw_methods', array( $this, 'add_withdraw_method' ), 10 );
    add_action( 'dokan_store_profile_saved', array( $this, 'save_withdraw_options' ), 50, 2 );
    add_action( 'option_dokan_withdraw', array( $this, 'active_juno_withdraw' ) );
    add_filter( 'dokan_ajax_settings_response', array( $this, 'custom_save_message' ) );
  }

  public function add_withdraw_method( $methods ) {
    $methods['juno'] = array(
      'title'    => __( 'Juno', 'juno-split' ),
      'callback' => array( $this, 'juno_settings' )
    );

    return $methods;
  }


  public function juno_settings( $store_settings ) {
    $token = get_user_meta( get_current_user_id(), '_juno_token', true );
    ?>
    <div class="dokan-form-group">
      <div class="dokan-w8">
        <input name="settings[juno][token]" value="<?php echo esc_attr( $token ); ?>" class="dokan-form-control" placeholder="<?php esc_attr_e( 'Seu token Juno', 'juno-split' ); ?>" type="text" />
        <p class="description"><?php echo wc_juno_marketplace_token_message( $token ); ?></p>
      </div>
    </div>
    <?php
  }

  public function save_withdraw_options( $store_id, $dokan_settings ) {
    if ( ! $store_id ) {
      return;
    }

    if ( isset( $_POST['settings']['juno']['token'] ) ) {
      $dokan_settings['payment']['juno'] = $_POST['settings']['juno'];
      update_user_meta( $store_id, 'dokan_profile_settings', $dokan_settings );
      update_user_meta( $store_id, '_juno_token', esc_attr( $_POST['settings']['juno']['token'] ) );
    }
  }


  public function active_juno_withdraw( $settings ) {
    if ( isset( $settings['withdraw_methods'] ) ) {
      $juno = array(
        'juno' => 'juno'
      );
      $settings['withdraw_methods'] = $juno + $settings['withdraw_methods'];
    }

    return $settings;
  }


  public function custom_save_message( $result ) {
    if ( isset( $result['msg'], $_POST['settings']['juno']['token'] ) ) {
      $result['msg'] .= ' <strong style="text-decoration: underline;">' . __( 'Ao alterar seu token na Juno é necessário atualizá-lo em nosso marketplace, caso contrário seus pagamentos não serão processados.', 'juno-split' ) . '</strong>';
    }

    return $result;
  }
}

new Juno_Dokan_Settings();
