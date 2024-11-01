<?php

namespace WPV_SKW\Core;

use \WC_Settings_Page;

/**
 * SettingsBase
 */
class Settings extends WC_Settings_Page {

	private $settings = [];


	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		$this->id    = 'wpv_skw';
		$this->label = __( 'Swiss Knife', 'woocommerce' );

		$this->register_settings();

		parent::__construct();
	}

	/**
	 * Get sectionn to be added
	 *
	 * @return void
	 */
	public function get_sections() {

		$sections = [
			''             => __( ' General', 'woocommerce' ),
			'repeat_order' => __( 'Repeat Order', 'woocommerce' ),
			'cancel_order' => __( 'Cancel Order', 'woocommerce' ),
		];

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}


	/**
	 * Generate output for sections
	 *
	 * @return void
	 */
	public function output() {

		$settings = $this->get_settings();

		\WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings
	 *
	 * @return void
	 */
	public function save() {

		$settings = $this->get_settings();

		\WC_Admin_Settings::save_fields( $settings );
	}

	public function get_settings() {

		global $current_section;

		if ( isset( $this->settings[ $current_section ] ) ) {

			return $this->settings[ $current_section ];
		}

		return [];
	}

	public function register_settings() {

		$this->settings = apply_filters( 'wpvskw/settings', $this->settings );
	}
}
