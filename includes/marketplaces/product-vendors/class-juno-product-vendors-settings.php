<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Single Marketplace settings
 */
class Juno_Product_Vendors_Settings extends WC_Juno_Marketplace_Settings {
  function hooks() {
    add_action( 'admin_enqueue_scripts', array( $this, 'juno_scripts' ), 200 );

    // add fields to taxonomy edit page.
    add_action( 'wcpv_product_vendors_edit_form_fields', array( $this, 'edit_vendor_fields' ) );

    add_action( 'woocommerce_order_status_processing', array( $this, 'order_paid' ), 10, 2 );
  }

  // este método não possui filtros...
  public function juno_scripts() {
    global $current_screen;
    if ( ! isset( $current_screen->base ) || 'toplevel_page_wcpv-vendor-settings' !== $current_screen->base ) {
      return;
    }

    $vendor_data = WC_Product_Vendors_Utils::get_vendor_data_from_user();
    $token       = isset( $vendor_data['juno_token'] ) ? $vendor_data['juno_token'] : '';
    $token       = isset( $_POST['vendor_data']['juno_token'] ) ? $_POST['vendor_data']['juno_token'] : $token;

    ob_start();
    ?>
      jQuery(document).ready(function($) {
        $( "#wcpv-vendor-settings .form-table" ).append('<tr class="form-field"> \
          <th scope="row" valign="top"><label for="juno_token"><?php esc_attr_e( 'Seu token Juno', 'juno-split' ); ?></label></th> \
          <td> \
            <input type="text" id="juno_token" name="vendor_data[juno_token]" value="<?php echo $token; ?>" /> \
            <p><?php echo wc_juno_marketplace_token_message( $token ); ?></p> \
          </td> \
        </tr> \
        ');
      });
    <?php
    $data = ob_get_clean();

    wp_add_inline_script( 'wcpv-vendor-admin-scripts', $data, 'after' );
  }



  public function edit_vendor_fields( $term ) {
    $vendor_data = get_term_meta( $term->term_id, 'vendor_data', true );
    $token       = isset( $vendor_data['juno_token'] ) ? $vendor_data['juno_token'] : '';

    ?>
    <tr class="form-field">
      <th scope="row" valign="top"><label for="juno_token"><?php esc_attr_e( 'Token Juno', 'juno-split' ); ?></label></th>

      <td>
        <input type="text" id="juno_token" name="vendor_data[juno_token]" value="<?php echo $token; ?>" />

        <p><?php _e( 'Informe o token do vendedor para receber pagamentos.', 'juno-split' ) ?></p>
      </td>
    </tr>
    <?php
  }


  public function order_paid( $order_id, $order ) {
    if ( apply_filters( 'wc_juno_marketplace_update_commission', true ) ) {
      $split       = new Juno_Product_Vendors();
      $commissions = $split->get_commission_by_order_id( $order_id, true );

      foreach ( $commissions as $commission ) {
        $split->update_order_commissions( $commission->id, $commission->order_item_id );
      }
    }
  }
}

new Juno_Product_Vendors_Settings();
