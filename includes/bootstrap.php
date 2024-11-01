<?php

namespace WPV_SKW;

use WPV_SKW\ModuleManager;
use WPV_SKW\Core\Settings;

class Plugin {

	private static $instance = null;

	private static $config;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		new ModuleManager();

		add_filter(
			'woocommerce_get_settings_pages',
			function ($settings) {
				$settings[] = new Settings();
				return $settings;
			}
		);
	}
}
