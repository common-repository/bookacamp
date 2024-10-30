<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://bookacamp.de/
 * @since      1.0.0
 *
 * @package    Wp_Bookacamp
 * @subpackage Wp_Bookacamp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Bookacamp
 * @subpackage Wp_Bookacamp/includes
 * @author     Mathias Methner, Bookacamp <support@bookacamp.de>
 */
class Wp_Bookacamp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-bookacamp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


}
