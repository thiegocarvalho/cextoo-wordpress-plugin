<?php

/**
 *
 * @link       https://github.com/thiegocarvalho
 * @since      0.1.0
 *
 * @package    Cextoo
 * @subpackage Cextoo/includes
 */

/**
 * @since      0.1.0
 * @package    Cextoo
 * @subpackage Cextoo/includes
 * @author     ThiegoCarvalho <carvalho.thiego@gmail.com>
 */
class Cextoo
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Cextoo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function __construct()
	{
		if (defined('CEXTOO_VERSION')) {
			$this->version = CEXTOO_VERSION;
		} else {
			$this->version = '0.1.2';
		}
		$this->plugin_name = 'cextoo-plugin';

		$this->load_dependencies();
		$this->set_locale();
		$this->set_api();
		$this->set_cron_jobs();
		$this->set_shortcodes();
		$this->set_email_type();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cextoo_Loader. Orchestrates the hooks of the plugin.
	 * - Cextoo_i18n. Defines internationalization functionality.
	 * - Cextoo_Admin. Defines all hooks for the admin area.
	 * - Cextoo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cextoo-plugin-loader.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cextoo-plugin-i18n.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cextoo-plugin-database.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cextoo-plugin-api.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cextoo-plugin-shortcode.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cextoo-plugin-template.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cextoo-plugin-crons.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cextoo-plugin-admin.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cextoo-plugin-public.php';

		$this->loader = new Cextoo_Loader();
	}

	private function set_api()
	{
		$plugin_api = new Cextoo_API();

		$this->loader->add_action('rest_api_init', $plugin_api, 'set_endpoints');
	}

	private function set_cron_jobs()
	{
		$cron_class = new Cextoo_Crons();
		foreach ($cron_class->jobs as $job) {
			$this->loader->add_action($job, $cron_class, $job);
		}
	}

	private function set_shortcodes()
	{
		$plugin_shortcode = new Cextoo_Shortcode();

		$this->loader->add_action('init', $plugin_shortcode, 'register_shortcodes');
	}

	public function set_email_type_function()
	{
		return 'text/html';
	}

	private function set_email_type()
	{
		$this->loader->add_filter('wp_mail_content_type', $this, 'set_email_type_function');
	}

	private function set_locale()
	{

		$plugin_i18n = new Cextoo_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Cextoo_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_page');
		$this->loader->add_action('admin_init', $plugin_admin, 'register_options');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Cextoo_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Cextoo_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}