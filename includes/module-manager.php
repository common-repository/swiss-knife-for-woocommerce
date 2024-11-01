<?php

namespace WPV_SKW;

class ModuleManager {

	protected $modules = [];

	public function __construct() {
		$this->set_modules();

		$this->init_modules();
	}

	protected function set_modules() {

		$saved_modules = get_option( 'skw_modules', [] );

		$this->modules['system'] = [
			'module-manager' => [
				'label' => 'Module Manager',
			],
		];

		$this->modules['general'] = [
			'repeat-order' => [
				'label'  => 'Repeat Order',
				'config' => 'repeat_order',
			],
			'cancel-order' => [
				'label'  => 'Cancel Order',
				'config' => 'cancel_order',
			],
		];

		// set active status
		foreach ( $this->modules['general'] as $module_id => $module ) {

			if ( isset( $saved_modules[ $module_id ] ) ) {
				$this->modules['general'][ $module_id ]['active'] = $saved_modules[ $module_id ]['active'];
			} else {
				$this->modules['general'][ $module_id ]['active'] = 1;
			}
		}
	}

	public function get_modules() {

		return $this->modules;
	}



	protected function init_modules() {
		$this->modules = apply_filters( 'wpvskw/modules', $this->modules );

		foreach ( $this->modules['system'] as $key => $module_name ) {

			if ( 1 ) {
				$class_name = str_replace( '-', ' ', $key );
				$class_name = str_replace( ' ', '', ucwords( $class_name ) );
				$class_name = 'WPV_SKW\Modules\\' . $class_name . '\Module';

				$this->modules[ $key ] = $class_name::instance();
			}
		}

		foreach ( $this->modules['general'] as $module_id => $module ) {

			if ( $module['active'] ) {
				$class_name = str_replace( '-', ' ', $module_id );
				$class_name = str_replace( ' ', '', ucwords( $class_name ) );
				$class_name = 'WPV_SKW\Modules\\' . $class_name . '\Module';
				$class_name::instance();
			}
		}
	}
}
