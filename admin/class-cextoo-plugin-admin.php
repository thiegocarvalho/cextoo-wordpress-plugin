<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/thiegocarvalho
 * @since      1.0.0
 *
 * @package    Cextoo_Plugin
 * @subpackage Cextoo_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cextoo_Plugin
 * @subpackage Cextoo_Plugin/admin
 * @author     ThiegoCarvalho <carvalho.thiego@gmail.com>
 */
class Cextoo_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    public function generate_token(  )
    {
        return "0x". substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 32);
    }


    public function register_options(){

        register_setting(
            'cextoo', // Option group
            'cextoo_token', // Option name
            [ $this, 'generate_token' ]
        );
    }


    public function render_admin_page()
    {
       include_once 'partials/cextoo-plugin-admin-display.php';
    }

    public function add_admin_page(){
        add_menu_page(
            __( 'Cextoo', 'cextoo' ),
            __( 'Cextoo', 'cextoo' ),
            'manage_options',
            'cextoo',
            [$this, 'render_admin_page'],
            plugins_url( 'cextoo-wordpress-plugin/admin/images/cextoo-icon.png' ),
            99
        );
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cextoo-plugin-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cextoo-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}

}
