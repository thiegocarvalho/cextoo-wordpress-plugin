<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/thiegocarvalho
 * @since      1.0.0
 *
 * @package    Cextoo_Plugin
 * @subpackage Cextoo_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cextoo_Plugin
 * @subpackage Cextoo_Plugin/includes
 * @author     ThiegoCarvalho <carvalho.thiego@gmail.com>
 */
class Cextoo_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.1.0
	 */
	public static function activate() {

		//TODO: Verify if the table exists and if not, create it.
		// Create Cextoo Table
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE `{$wpdb->base_prefix}cextoo` (
			ID bigint(20) unsigned NOT NULL auto_increment,
			external_id varchar(250) NOT NULL,
			product_name varchar(250) NOT NULL,
			status int(11) NOT NULL default '0',
			renew_count int(11) NOT NULL default '0',
			renew_at datetime NULL,
			start_at datetime NOT NULL,
			expires_at datetime NULL,
			user_id bigint(20) UNSIGNED NOT NULL,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (ID)
		  ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}
}
