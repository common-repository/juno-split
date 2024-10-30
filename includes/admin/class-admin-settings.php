<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class WC_Juno_Marketplace_Main_Settings {
  function __construct() {
    add_action( 'woo_juno_general_settings', array( $this, 'custom_settings' ) );
  }

  public function custom_settings( $settings ) {
    $marketplace = array(
      array(
        'title'        => __( 'Integração com Marketplace', 'juno-split' ),
        'type'         => 'title',
        'description'  => __( 'Ajuste os detalhes da sua integração.', 'juno-split' ),
        'id'           => 'account_registration_options',
      ),
      'split_log' => array(
        'title'       => __( 'Log para split', 'woo-juno' ),
        'type'        => 'checkbox',
        'label'       => __( 'Habilitar', 'woo-juno' ),
        'description' => __( 'Registrar os eventos de configuração de split de pagamento.', 'woo-juno' ),
        'default'     => 'no'
      ),
      'charge_fee' => array(
        'title'       => __( 'Cobrar taxa dos vendedores', 'woo-juno' ),
        'type'        => 'checkbox',
        'label'       => __( 'Habilitar', 'woo-juno' ),
        'description' => __( 'Se ativo, o vendedor pagará a taxa Juno referente a sua parte do pagamento. Caso contrário, o marketplace pagará toda a taxa.', 'woo-juno' ),
        'default'     => 'no'
      ),

      // array(
      //   'type' => 'sectionend',
      //   'id'   => 'account_registration_options',
      // ),
    );

    return $settings + $marketplace;
  }
}

new WC_Juno_Marketplace_Main_Settings();

