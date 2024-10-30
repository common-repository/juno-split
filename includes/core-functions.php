<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Plugin functions.
 */
function wc_juno_marketplace_token_message( $token = '' ) {
  $message = __( 'Você precisa informar seu <strong>token privado</strong> da Juno para receber pagamentos.', 'juno-split' );

  if ( $token ) {
    $message .= '<br /><strong style="text-decoration: underline;">';
    $message .= __( 'Ao alterar seu token na Juno é necessário atualizá-lo em nosso marketplace, caso contrário seus pagamentos não serão processados.', 'juno-split' );
    $message .= '</strong>';
  }

  return apply_filters( 'wc_juno_marketplace_token_message', $message );
}