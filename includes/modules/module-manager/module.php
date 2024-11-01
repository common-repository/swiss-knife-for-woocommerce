<?php

namespace WPV_SKW\Modules\ModuleManager;

use WPV_SKW;
use WPV_SKW\ModuleManager;

class Module {



	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Add Settings Page.
		add_action( 'admin_menu', [ $this, 'settings_menu' ] );

		add_action( 'in_admin_header', [ $this, 'admin_header' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// register ajax
		add_action( 'wp_ajax_skw_module_manager', [ $this, 'ajax_manager' ] );
	}

	public function settings_menu() {

		add_submenu_page(
			'options-general.php',
			__( 'Swiss Knife for Woo', 'wpv-skw' ),
			__( 'Swiss Knife for Woo', 'wpv-skw' ),
			'manage_options',
			'wpvskw-settings',
			[ $this, 'settings_page' ]
		);
	}

	public function settings_page() {

		$this->module_box();
	}

	public function admin_header() {

		$screen = get_current_screen();

		if ( $screen->id !== 'settings_page_wpvskw-settings' ) {
			return;
		}

		?>
		<div class="skw-topbar">
			<div class="skw-branding">
				<?php echo file_get_contents(WPV_SKW_PATH . 'assets/img/skw-logo.svg'); ?>
				<h1><?php echo esc_html( 'Swiss Knife for WooCommerce' ); ?></h1>
				<span class="skw-version"><?php echo esc_attr( WPV_SKW_VERSION ); ?></span>
			</div>
		</div>
		<?php
	}

	public function module_box() {
		$module_manager = new ModuleManager();
		$modules        = $module_manager->get_modules();

		?>
		<div class="skw-wrap">
			<div class="skw-content-wrapper">

				<div class="skw-settings-main-wrapper">
					<div class="skw-tabs">
						<h3 class="skw-title active"><?php echo esc_attr__( 'Modules', 'wpv-skw' ); ?></h3>
						<h3 class="skw-title skw-settings"><a href="<?php echo esc_html( admin_url( 'admin.php?page=wc-settings&tab=wpv_skw' ) ); ?>"><?php echo esc_attr__( 'Settings', 'wpv-skw' ); ?></a></h3>
					</div>

					<div class="skw-settings-box skw-metabox">
						<div class="skw-metabox-content">
							<form action="" class="aep-tab-content active" id="skw-module-manager">

								<?php
								foreach ( $modules['general'] as $module_id => $module ) {

									if ( ! isset( $module['active'] ) || $module['active'] === 1 ) {
										$action = 'deactivate';
										$class  = 'skw-enabled';
										$text   = __( 'Deactivate', 'wpv-skw' );
									} else {
										$action = 'activate';
										$class  = 'skw-disabled';
										$text   = __( 'Activate', 'wpv-skw' );
									}

									?>
									<div class="skw-module-row <?php echo esc_attr( $class ); ?>">
										<!-- <input class="skw-module-item" type="checkbox" name="skw_modules[]" value="<?php echo esc_attr( $module_id ); ?>"> -->
										<?php
										echo esc_attr( $module['label'] );
										?>

										<?php
										if ( ! empty( $module['config'] ) ) {
											$config_url = esc_url( admin_url( 'admin.php?page=wc-settings&tab=wpv_skw&section=' . $module['config'] ) );
											?>
											<a class="skw-module-config" target="_blank" title="Configure" href="<?php echo esc_url( $config_url ); ?>"><span class="dashicons dashicons-admin-generic"></span></a>
											<?php
										}
										?>

										<div class="skw-module-action">
											<a data-action="<?php echo esc_attr( $action ); ?>" data-moduleid="<?php echo esc_attr( $module_id ); ?>" href="#"> <?php echo esc_attr( $text ); ?> </a>
										</div>
									</div>
									<?php
								}
								?>
							</form>
						</div>
					</div>
				</div>


			</div>
		</div>
		<?php
	}

	public function enqueue_scripts() {

		wp_enqueue_style( 'skw-module-manager', WPV_SKW_URL . 'assets/css/admin.css', [], WPV_SKW_VERSION );

		wp_register_script( 'skw-module-manager', WPV_SKW_URL . 'assets/js/module-manager.js', [ 'jquery' ], WPV_SKW_VERSION, true );

		wp_localize_script(
			'skw-module-manager',
			'skw_module',
			[
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'skw_mm_nonce' => wp_create_nonce( 'skw_mm_nonce' ),
			]
		);

		wp_enqueue_script( 'skw-module-manager' );
	}

	public function ajax_manager() {

		check_ajax_referer( 'skw_mm_nonce', '_wpnonce' );

		$module_action = sanitize_text_field( $_POST['module_action'] );

		if ( $module_action === 'activate' ) {

			$module_id = sanitize_text_field( $_POST['module_id'] );

			$modules = $this->activate_module( $module_id );
			wp_send_json(
				[
					'success' => true,
					'modules' => $modules,
				]
			);
		} elseif ( $module_action === 'deactivate' ) {

			$module_id = sanitize_text_field( $_POST['module_id'] );
			$modules   = $this->deactivate_module( $module_id );
			wp_send_json(
				[
					'success' => true,
					'modules' => $modules,
				]
			);
		}
	}

	private function activate_module( $module_id ) {

		// get from db
		$modules = get_option( 'skw_modules', [] );

		$modules[ $module_id ]['active'] = 1;

		update_option( 'skw_modules', $modules, true );

		return $modules;
	}

	private function deactivate_module( $module_id ) {

		// get from db
		$modules = get_option( 'skw_modules', [] );

		$modules[ $module_id ]['active'] = 0;

		update_option( 'skw_modules', $modules, true );

		return $modules;
	}
}
