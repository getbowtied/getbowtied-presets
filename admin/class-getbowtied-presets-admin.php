<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://getbowtied.com/
 * @since      1.0.0
 *
 * @package    Getbowtied_Presets
 * @subpackage Getbowtied_Presets/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Getbowtied_Presets
 * @subpackage Getbowtied_Presets/admin
 * @author     GetBowtied <vanesa@getbowtied.com>
 */
class Getbowtied_Presets_Admin {

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

		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'after_setup_theme', array( $this, 'customize_include' ) );
		add_action( 'after_setup_theme', array( $this, 'presets_redirect_url' ) );
	}

	/**
	 * Creates dashboard menu item
	 *
	 * @since    1.0.0
	 */
	function add_plugin_page() {
		add_options_page( 'Presets', 'Presets', 'manage_options', 'getbowtied-presets', 'Getbowtied_Presets_Admin::presets_options' );
	}

	/**
	 * Admin Presets page
	 *
	 * @since    1.0.0
	 */
	public static function presets_options() {
		if( !isset($_GET['changeset_id']) || !is_numeric($_GET['changeset_id']) ) {
			include dirname( __FILE__ ) . '/partials/main.php';
		}
	}

	/**
	 * Presets redirect URL
	 *
	 * @since    1.0.0
	 */
	function customize_include() {
		if (isset($_REQUEST['customize_changeset_uuid'])) {
			_wp_customize_include();
		}
	}

	function presets_redirect_url() {
		if (isset($_REQUEST['customize_changeset_uuid_gbt'])) {
			$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$parsed = explode('?', $current_url);
			$parsed[1]= 'customize_changeset_uuid=' . $_GET['customize_changeset_uuid_gbt'];
			$parsed = implode($parsed, '?');
			wp_redirect( $parsed );
			exit();
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/getbowtied-presets-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/getbowtied-presets-admin.js', array( 'jquery' ), $this->version, false );
	}

}
