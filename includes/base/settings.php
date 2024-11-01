<?php

namespace WPV_SKW\Base;

abstract class Settings {

	protected $id = 'wpv_skw';

	public function __construct() {
		add_filter( 'wpvskw/settings', [ $this, 'get_settings' ] );
	}
}
