<?php

namespace NDS_Advanced_Search\Inc\Core;
use NDS_Advanced_Search as NS;

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.

 * @link       http://nuancedesignstudio.in
 * @since      1.0.0
 *
 * @author     Karan NA Gupta
 */
class Activator {

	/**
	 * Activation Hook.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$min_php = '5.6.0';
		$plugin_name = NS\PLUGIN_NAME;

		// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
		if ( version_compare( PHP_VERSION, $min_php, '<' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( 'This plugin requires a minmum PHP Version of ' . $min_php );
		}

		$default_options = array(
			'post' => 1,
		);
		update_option( $plugin_name, $default_options );

	}

}
