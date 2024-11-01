<?php

/**
 * Plugin Name: Swiss Knife for WooCommerce
 * Plugin URI: https://wpvibes.com/
 * Description: A well crafted bundle of WooCommerce Utilities
 * Version: 0.2
 * Author: WPVibes
 * Author URI: https://wpvibes.com
 * Text Domain: wpv-skw
 * Domain Path: /i18n/languages/
 * Requires at least: 5.4
 * Requires PHP: 5.6 or higher
 *
 * @package WPV_SKW
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WPV_PLUGIN_FILE' ) ) {
	define( 'WPV_PLUGIN_FILE', __FILE__ );
}

// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
class WPV_SKW {

	public function __construct() {
		define( 'WPV_SKW_VERSION', '0.2' );
		define( 'WPV_SKW_URL', plugins_url( '/', __FILE__ ) );
		define( 'WPV_SKW_PATH', plugin_dir_path( __FILE__ ) );
		define( 'WPV_SKW_FILE', __FILE__ );
		define( 'WPV_SKW_PLUGIN_BASE', plugin_basename( __FILE__ ) );
		define( 'WPV_SKW_SCRIPT_SUFFIX', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
		define( 'WPV_SKW_PHP_VERSION_REQUIRED', '5.6' );
		define( 'WPV_SKW_WOO_MIN_VERSION', '1.3.4' );

		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}


	private function includes() {
		require_once WPV_SKW_PATH . '/vendor/autoload.php';
		require_once WPV_SKW_PATH . '/includes/bootstrap.php';

		WPV_SKW\Plugin::instance();
	}

	public function plugins_loaded() {

		if ( class_exists( 'woocommerce' ) ) {
			$this->includes();
		} else {
			add_action( 'admin_notices', [ $this, 'plugin_error_notice' ] );
		}
	}

	public function plugin_error_notice() {
		?>
			<div class="error notice">
				<p><?php esc_attr_e( 'WooCommerce is not activated. Please install WooCommerce first, To use Swiss Knife for WooCommerce Plugin.', 'wpv_skw' ); ?></p>
			</div>
		<?php
	}

}




new WPV_SKW();
