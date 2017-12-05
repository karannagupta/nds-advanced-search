<?php

namespace NDS_Advanced_Search\Inc\Core;
use NDS_Advanced_Search as NS;

/**
 * Fired during plugin deactivation
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       http://nuancedesignstudio.in
 * @since      1.0.0
 *
 * @author     Karan NA Gupta
 */

class Deactivator {

	/**
	 * Deactivation Hook.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$plugin_name = NS\PLUGIN_NAME;
		$plugin_options_exist = get_option( $plugin_name );
		if ( $plugin_options_exist ) {
			delete_option( $plugin_name );
		}
	}

}
