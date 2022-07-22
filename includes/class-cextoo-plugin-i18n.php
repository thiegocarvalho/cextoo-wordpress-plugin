<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/thiegocarvalho
 * @since      1.0.0
 *
 * @package    Cextoo_Plugin
 * @subpackage Cextoo_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cextoo_Plugin
 * @subpackage Cextoo_Plugin/includes
 * @author     ThiegoCarvalho <carvalho.thiego@gmail.com>
 */
class Cextoo_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'Cextoo-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
