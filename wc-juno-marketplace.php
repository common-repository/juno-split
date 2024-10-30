<?php
/**
 * Plugin Name:          Juno Split para WooCommerce
 * Plugin URI:           https://wordpress.org/plugins/juno-split/
 * Description:          Utilize Juno com split de pagamentos no Dokan, WC Vendors e WooCommerce Marketplace
 * Author:               Juno Gateway de Pagamentos para ecommerces
 * Author URI:           https://juno.com.br
 * Version:              1.1.4
 * License:              GPLv3
 * License URI:          http://www.gnu.org/licenses/gpl-3.0.html
 * WC requires at least: 3.5.0
 * WC tested up to:      5.1.0
 * Text Domain:          juno-split
 * Domain Path:          /languages
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this plugin. If not, see
 * <https://www.gnu.org/licenses/gpl-2.0.txt>.
 *
 * @package WC_Juno_Marketplace
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

class WC_Juno_Marketplace {
  /**
   * Marketplace class
   *
   * @var string
   */
  protected static $marketplace = '';

  /**
   * Version.
   *
   * @var float
   */
  const VERSION = '1.1.4';

  /**
   * Instance of this class.
   *
   * @var object
   */
  protected static $instance = null;
  /**
   * Initialize the plugin public actions.
   */
  function __construct() {
    add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

    if ( ! class_exists( 'WooCommerce' ) ) {
      add_action( 'admin_notices', array( $this, 'woocommerce_fallback_notice' ) );
    } elseif ( ! class_exists( 'WC_Juno\Core\Plugin' ) ) {
      add_action( 'admin_notices', array( $this, 'juno_fallback_notice' ) );
    } else {
      if ( is_admin() ) {
        $this->admin_includes();
      }

      $this->includes();
      add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

      $this->marketplace_includes();
    }
  }

  public function includes() {
    // framework
    include_once 'includes/core-functions.php';
    include_once 'includes/class-process-split.php';
    include_once 'includes/marketplaces/class-marketplaces.php';
    include_once 'includes/marketplaces/class-marketplaces-settings.php';
  }


  /**
   * Load Marketplace specific classes
   */
  public function marketplace_includes() {
    if ( class_exists( 'WeDevs_Dokan' ) ) {
      self::$marketplace = 'Juno_Dokan';

      include_once 'includes/marketplaces/dokan/class-juno-dokan.php';
      include_once 'includes/marketplaces/dokan/class-juno-dokan-settings.php';

    } elseif ( class_exists( 'WC_Product_Vendors' ) ) {
      self::$marketplace = 'Juno_Product_Vendors';

      include_once 'includes/marketplaces/product-vendors/class-juno-product-vendors.php';
      include_once 'includes/marketplaces/product-vendors/class-juno-product-vendors-settings.php';

    } elseif ( class_exists( 'WC_Vendors' ) ) {
      self::$marketplace = 'Juno_WC_Vendors';

      include_once 'includes/marketplaces/wc-vendors/class-juno-wc-vendors.php';
      include_once 'includes/marketplaces/wc-vendors/class-juno-wc-vendors-settings.php';
    } elseif ( class_exists( 'WCFMmp' ) ) {
      self::$marketplace = 'Juno_WCFM';

      include_once 'includes/marketplaces/wcfm/class-juno-wcfm.php';
      include_once 'includes/marketplaces/wcfm/class-juno-wcfm-settings.php';
    } else {
      // notice: nenhum marketplace compatível encontrado!
    }
  }


  /**
   * Get custom marketplace class
   */
  public static function get_marketplace_class() {
    return self::$marketplace;
  }


  /**
   * Load the plugin text domain for translation.
   */
  public function load_plugin_textdomain() {
    $locale = apply_filters( 'plugin_locale', get_locale(), 'juno-split' );

    load_textdomain( 'juno-split', trailingslashit( WP_LANG_DIR ) . 'wc-juno-marketplace/wc-juno-marketplace-' . $locale . '.mo' );
    load_plugin_textdomain( 'juno-split', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  }


  /**
   *
   * Admin includes
   */
  public function admin_includes() {
    $api = new \WC_Juno\Service\Juno_REST_API();

    if ( ! isset( $api->version ) || 2 !== $api->version ) {
      add_action( 'admin_notices', array( $this, 'juno_upgrade_api_notice' ) );
    }

    include_once 'includes/admin/class-admin-settings.php';
  }


  /**
   * Return an instance of this class.
   *
   * @return object A single instance of this class.
   */
  public static function get_instance() {
    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * Get main file.
   *
   * @return string
   */
  public static function get_main_file() {
    return __FILE__;
  }

  /**
   * Get plugin path.
   *
   * @return string
   */

  public static function get_plugin_path() {
    return plugin_dir_path( __FILE__ );
  }

  /**
   * Get the plugin url.
   * @return string
   */
  public static function plugin_url() {
    return untrailingslashit( plugins_url( '/', __FILE__ ) );
  }

  /**
   * Get the plugin dir url.
   * @return string
   */
  public static function plugin_dir_url() {
    return plugin_dir_url( __FILE__ );
  }

  /**
   * Get templates path.
   *
   * @return string
   */
  public static function get_templates_path() {
    return self::get_plugin_path() . 'templates/';
  }


  /**
   * Action links.
   *
   * @param  array $links Default plugin links.
   *
   * @return array
   */
  public function plugin_action_links( $links ) {
    $plugin_links   = array();
    $plugin_links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=integration&section=juno-integration' ) ) . '">' . __( 'Configurações', 'juno-split' ) . '</a>';
    return array_merge( $plugin_links, $links );
  }


  /**
   * WooCommerce fallback notice.
   *
   * @return string Fallack notice.
   */
  public function woocommerce_fallback_notice() {
    echo '<div class="error"><p>' . sprintf( __( 'Juno Split depende do plugin <strong>%s</strong> para funcionar!', 'juno-split' ), '<a href="https://wordpress.org/plugins/woocommerce/">' . __( 'WooCommerce', 'juno-split' ) . '</a>' ) . '</p></div>';
  }

  /**
   * Juno missing notice.
   */
  public static function juno_fallback_notice() {
    echo '<div class="error"><p>' . sprintf( __( 'Juno Split depende do plugin <strong>%s</strong> para funcionar!', 'juno-split' ), '<a href="https://wordpress.org/plugins/woo-juno/">' . __( 'Juno for WooCommerce', 'juno-split' ) . '</a>' ) . '</p></div>';
  }

  /**
   * Juno missing notice.
   */
  public static function juno_upgrade_api_notice() {
    echo '<div class="error" style="background: #dc3232; color: #fff; border: none;"><p>' . sprintf( __( 'Juno Split requer a versão 1.1.x do plugin <strong>%s</strong> configurada com a API 2.0. Atualize o plugin e %s.', 'juno-split' ),
      'Juno for WooCommerce',
      '<a style="color: #fff" href="' . admin_url( 'admin.php?page=wc-settings&tab=integration&section=juno-integration' ) . '">' . __( 'atualize as configurações', 'juno-split' ) . '</a>'
    ) . '</p></div>';
  }
}

add_action( 'plugins_loaded', array( 'WC_Juno_Marketplace', 'get_instance' ) );
