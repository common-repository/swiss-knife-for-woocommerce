<?php

namespace WPV_SKW\Modules\RepeatOrder;

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

		$this->section_id = 'repeat_order';
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
				'title' => __( 'Repeat Order', 'woocommerce' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'skw-repeat-order',
			],

			[
				'title'   => __( 'Show on Order List', 'wpv_skw' ),
				'desc'    => '',
				'id'      => $field_name . '[show_on_order_list]',
				'default' => 'yes',
				'type'    => 'checkbox',
			],

			[
				'title'   => __( 'Order Status', 'wpv_skw' ),
				'desc'    => '',
				'id'      => $field_name . '[order_status]',
				'default' => ['completed'],
				'type'    => 'multiselect',
				'options' => $this->get_order_status(),
			],

			[
				'title'   => __( 'Button Text', 'wpv_skw' ),
				'desc'    => '',
				'id'      => $field_name . '[button_text]',
				'default' => 'Repeat Order',
				'type'    => 'text',
			],

			[
				'title'    => __( 'Cart Custom Notice', 'wpv_skw' ),
				'desc_tip' => 'Display custom notice after order again button redirect',
				'id'       => $field_name . '[cart_notice]',
				'default'  => 'Repeat Order',
				'type'     => 'text',
			],

			[
				'title'    => __( 'Cart Type', 'wpv_skw' ),
				'desc_tip' => 'Choose option to merge or override cart',
				'id'       => $field_name . '[cart_product]',
				'default'  => 'replace_cart',
				'type'     => 'select',
				'options'  => [
					'replace_cart' => 'Replace Cart',
					'merge_cart'   => 'Merge Cart',
				],
			],

			[
				'title'    => __( 'Redirect To ', 'wpv_skw' ),
				'desc_tip' => 'Choose option to redirect order again',
				'id'       => $field_name . '[redirect_to]',
				'default'  => 'cart',
				'type'     => 'select',
				'options'  => [
					'cart'     => 'Cart',
					'checkout' => 'Checkout',
				],
			],

			[
				'title'   => __( 'Button background ', 'wpv_skw' ),
				'desc'    => '',
				'id'      => $field_name . '[button_color]',
				'default' => '#000',
				'type'    => 'color',
			],

			[
				'title'   => __( 'Button Color ', 'wpv_skw' ),
				'desc'    => '',
				'id'      => $field_name . '[text_color]',
				'default' => '#fff',
				'type'    => 'color',
			],

			[
				'type' => 'sectionend',
				'id'   => 'product_inventory_options',
			],

		];

		return $settings;
	}

	public function get_order_status() {

		$order_status = wc_get_order_statuses();

		foreach ( $order_status as $key => $val ) {
			$new_key                    = str_replace( 'wc-', '', $key );
			$updated_status[ $new_key ] = $val;
		}

		return $updated_status;
	}

}
