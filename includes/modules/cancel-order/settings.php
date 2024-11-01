<?php

namespace WPV_SKW\Modules\CancelOrder;

use WPV_SKW\Base\Settings as SettingsBase;

/**
 * Settings
 */
class Settings extends SettingsBase {

	private $secion_id;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {

		$this->section_id = 'cancel_order';

		parent::__construct();
	}

	/**
	 * Get module settings
	 *
	 * @return array
	 */
	public function get_settings( $settings ) {

		$field_name = $this->id . '_' . $this->section_id;

		$settings[ $this->section_id ] = [

			[
				'title' => __( 'Cancel Order', 'woocommerce' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'skw-cancel-order',
			],

			[
				'title'   => __( 'Order Status', 'wpv_skw' ),
				'desc'    => '',
				'id'      => $field_name . '[cancel_order_status]',
				'default' => [ 'pending', 'failed' ],
				'type'    => 'multiselect',
				'options' => $this->get_cancel_order_status(),
			],

			[
				'title'   => __( 'Button Text', 'wpv_skw' ),
				'desc'    => '',
				'id'      => $field_name . '[cancel_text]',
				'default' => 'Cancel Order',
				'type'    => 'text',
			],

			[
				'title'    => __( 'Custom Notice', 'wpv_skw' ),
				'desc_tip' => 'Display custom notice on order cancellation',
				'id'       => $field_name . '[cancel_notice]',
				'default'  => '',
				'type'     => 'text',
			],

			[
				'title'   => __( 'Button background ', 'wpv_skw' ),
				'desc'    => '',
				'id'      => $field_name . '[cancel_button_background]',
				'default' => '#000',
				'type'    => 'color',
			],

			[
				'title'   => __( 'Button Color ', 'wpv_skw' ),
				'desc'    => '',
				'id'      => $field_name . '[cancel_button_color]',
				'default' => '#fff',
				'type'    => 'color',
			],

			[
				'type' => 'sectionend',
				'id'   => 'product_cancel_option',
			],

		];

		return $settings;
	}

	public function get_cancel_order_status() {

		$order_status = wc_get_order_statuses();

		foreach ( $order_status as $key => $val ) {
			$new_key                    = str_replace( 'wc-', '', $key );
			$updated_status[ $new_key ] = $val;
		}
		return $updated_status;
	}


}
