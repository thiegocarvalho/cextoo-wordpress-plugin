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
class Cextoo_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.1.0
	 */
	public static function activate()
	{
		$crons = new Cextoo_Crons();
		$crons->cextoo_manager_subscriptions_job_activation();
		$crons->cextoo_manager_email_renew_job_activation();
		$database = new Cextoo_Database();
		$database->createDatabase();
	}
}