<?php

namespace WPV_SKW\Modules\RepeatOrder;

use WPV_SKW\Modules\RepeatOrder\Settings;

class Module {


	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {

		// Order Again
		add_filter( 'woocommerce_my_account_my_orders_actions', [ $this, 'my_account_order_action' ], 10, 2 );

		// Order status
		add_filter( 'woocommerce_valid_order_statuses_for_order_again', [ $this, 'order_again_status' ] );

		new Settings();
		// Order again notice
		add_filter( 'woocommerce_ordered_again', [ $this, 'order_cart_notice' ] );

		// merge or replace cart
		add_filter( 'woocommerce_empty_cart_when_order_again', [ $this, 'order_products' ] );

		// redirect to cart or checkout
		add_filter( 'woocommerce_get_cart_url', [ $this, 'redirect_url' ] );

		// button styling
		add_action( 'woocommerce_account_content', [ $this, 'order_again_button_style' ] );
	}

	public function my_account_order_action( $actions, $order ) {

		$settings = $this->get_settings();
		// return if re-order is not allowed
		if ( ! $order || ! $order->has_status( apply_filters( 'woocommerce_valid_order_statuses_for_order_again', [ $settings['order_status'] ] ) ) || ! is_user_logged_in() ) {
			return $actions;
		}

		$actions['skw-reorder'] = [
			'url'  => wp_nonce_url( add_query_arg( 'order_again', $order->get_id() ), 'woocommerce-order_again' ),
			'name' => $settings['button_text'],
		];

		return $actions;
	}

	public function order_cart_notice() {

		$settings = $this->get_settings();
		$notice   = $settings['cart_notice'];
		if ( ! empty( $notice ) && ( $settings['redirect_to'] === 'cart' || $settings['redirect_to'] === 'checkout' ) ) {
			wc_add_notice( $notice, 'success' );
		}
	}


	public function get_settings() {

		$defaults = [
			'show_on_order_list' => 'yes',
			'order_status'       => ['completed'],
			'button_text'        => __( 'Repeat Order', 'wpv-skw' ),
			'cart_notice'        => __( 'Your Cart is refilled', 'wpv-skw' ),
			'cart_product'       => 'replace_cart',
			'redirect_to'        => 'cart',
			'button_color'       => '#000',
			'text_color'         => '#fff',
		];

		$settings = get_option( 'wpv_skw_repeat_order', [] );

		$settings = wp_parse_args( $settings, $defaults );
		return $settings;
	}

	public function order_again_status( $statuses ) {

		$settings = $this->get_settings();

		return $settings['order_status'];
	}

	public function order_products() {

		$settings = $this->get_settings();

		$cart_status = $settings['cart_product'];

		if ( $cart_status === 'replace_cart' ) {
			return true;
		}
		if ( $cart_status === 'merge_cart' ) {
			return false;
		}
	}

	public function redirect_url( $url ) {

		$settings = $this->get_settings();

		if ( isset( $_GET['order_again'], $_GET['_wpnonce'] ) && is_user_logged_in() && wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'woocommerce-order_again' ) && $settings['redirect_to'] === 'checkout' ) {
			return wc_get_checkout_url();
		} else {
			return $url;
		}
	}

	public function order_again_button_style() {

		$settings = $this->get_settings();

		if ( ! empty( $settings['button_text'] ) ) {
			$bgcolor = $settings['button_color'];
			$color   = $settings['text_color'];
			echo '<style>
				.woocommerce-MyAccount-content .my_account_orders .button.skw-reorder{
					background-color:' . esc_attr( $bgcolor ) . ';
					color:' . esc_attr( $color ) . ';
				}
			</style>';
		}
	}

}
