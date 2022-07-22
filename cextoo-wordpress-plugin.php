<?php

/**
 *
 * @link              https://github.com/thiegocarvalho
 * @since             1.0.0
 * @package           Cextoo_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Cextoo Plugin
 * Plugin URI:        https://cextoo.com
 * Description:       Decoupling to your sales system.
 * Version:           1.0.0
 * Author:            ThiegoCarvalho
 * Author URI:        https://github.com/thiegocarvalho
 * GitHub Plugin URI: https://github.com/thiegocarvalho/cextoo-wordpress-plugin/
 * Primary Branch:    main
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cextoo-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'Cextoo_PLUGIN_VERSION', '1.0.0' );

function activate_Cextoo_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cextoo-plugin-activator.php';
	Cextoo_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cextoo-plugin-deactivator.php
 */
function deactivate_Cextoo_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cextoo-plugin-deactivator.php';
	Cextoo_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cextoo_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_cextoo_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cextoo-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cextoo_plugin() {

	$plugin = new Cextoo_Plugin();
	$plugin->run();

}
run_cextoo_plugin();
