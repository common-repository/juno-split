<h2><?php _e( 'Configurações Juno', 'juno-split' ); ?></h2>

<table class="form-table" role="presentation">
  <tr id="wc-juno-marketplace">
    <th><label for="_juno_token"><?php _e( 'Token Juno', 'juno-split' ); ?></label></th>
    <td>
      <input name="_juno_token" type="text" id="_juno_token" class="regular-text" value="<?php echo get_user_meta( $vendor->ID, '_juno_token', true ); ?>" autocomplete="off" />
    </td>
  </tr>
</table>
