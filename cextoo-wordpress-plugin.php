<?php

/**
 *
 * @link              https://github.com/thiegocarvalho
 * @since             0.1.0
 * @package           Cextoo
 *
 * @wordpress-plugin
 * Plugin Name:       Cextoo
 * Plugin URI:        https://cextoo.com
 * Description:       Decoupling to your sales system.
 * Version:           0.1.1
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
define( 'CEXTOO_VERSION', '0.1.1' );

function activate_cextoo_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cextoo-plugin-activator.php';
	Cextoo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cextoo-plugin-deactivator.php
 */
function deactivate_cextoo_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cextoo-plugin-deactivator.php';
	Cextoo_Deactivator::deactivate();
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
 * @since    0.1.0
 */
function run_cextoo_plugin() {

	$plugin = new Cextoo();
	$plugin->run();

}
run_cextoo_plugin();
