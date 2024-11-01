<?php

namespace WPV_SKW\Modules\CancelOrder;

use WPV_SKW\Modules\CancelOrder\Settings;

class Module {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {

		// cancel order button
		add_filter( 'woocommerce_valid_order_statuses_for_cancel', [ $this, 'my_account_cancel_order' ], 10, 2 );

		new Settings();

		// cancel button text
		add_filter( 'woocommerce_my_account_my_orders_actions', [ $this, 'cancel_order_text' ], 10, 2 );

		// cancel notice
		add_filter( 'woocommerce_order_cancelled_notice', [ $this, 'cancel_order_notice' ] );

		// button styling
		add_action( 'woocommerce_account_content', [ $this, 'cancel_button_style' ] );
	}

	public function my_account_cancel_order( $statuses ) {

		$settings = $this->get_settings();
		if ( ! empty( $settings['cancel_order_status'] ) ) {
			return $settings['cancel_order_status'];
		} else {
			return $statuses;
		}
	}

	public function cancel_order_text( $actions, $order ) {

		$settings = $this->get_settings();

		if ( isset( $actions['cancel'] ) && ! empty( $settings['cancel_text'] ) ) {
			$actions['cancel']['name'] = $settings['cancel_text'];
		}

		return $actions;
	}

	public function get_settings() {

		$defaults = [
			'cancel_order_status'      => [ 'pending', 'failed' ],
			'cancel_text'              => 'Cancel Order',
			'cancel_notice'            => 'Your order has been cancelled',
			'cancel_button_background' => '#000',
			'cancel_button_color'      => '#fff',

		];

		$settings = get_option( 'wpv_skw_cancel_order', [] );
		$settings = wp_parse_args( $settings, $defaults );

		return $settings;
	}

	public function cancel_order_notice() {

		$settings = $this->get_settings();

		$notice = $settings['cancel_notice'];
		wc_add_notice( $notice, 'notice' );
	}

	public function cancel_button_style() {
		$settings = $this->get_settings();

		if ( ! empty( $settings['cancel_text'] ) ) {
			$cancel_bgcolor = $settings['cancel_button_background'];
			$cancel_color   = $settings['cancel_button_color'];
			echo '<style>
				.woocommerce-MyAccount-content .my_account_orders .button.cancel{
					background-color:' . esc_attr( $cancel_bgcolor ) . ';
					color:' . esc_attr( $cancel_color ) . ';
				}	
			</style>';
		}
	}


}
