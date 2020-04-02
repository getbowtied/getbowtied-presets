<?php

/**
 * Plugin Name:       GetBowtied Presets
 * Plugin URI:        https://github.com/getbowtied/getbowtied-presets
 * Description:       A suite of tools to help you kickstart your GetBowtied theme.
 * Version:           1.0.2
 * Author:            GetBowtied
 * Author URI:        https://getbowtied.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       getbowtied-presets
 * Domain Path:       /languages
 *
 * @link              https://getbowtied.com/
 * @since             1.0.0
 * @package           Getbowtied_Presets
 */

if ( ! defined( 'WPINC' ) ) { die(); }

if ( !function_exists( '_wp_customize_include' ) ) {
    require_once ABSPATH . WPINC . '/theme.php';
}

if( !function_exists('wp_get_current_user') ) {
    include( ABSPATH . 'wp-includes/pluggable.php' );
}

define( 'GETBOWTIED_PRESETS_VERSION', '1.0.2' );

require_once plugin_dir_path( __FILE__ ) . 'includes/updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/getbowtied/getbowtied-presets/master/includes/updater/assets/plugin.json',
	__FILE__,
	'getbowtied-presets'
);

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Getbowtied_Presets
 * @subpackage Getbowtied_Presets/includes
 * @author     GetBowtied <vanesa@getbowtied.com>
 */
class Getbowtied_Presets {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Getbowtied_Presets_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GETBOWTIED_PRESETS_VERSION' ) ) {
			$this->version = GETBOWTIED_PRESETS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'getbowtied-presets';

		$this->load_dependencies();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Getbowtied_Presets_Loader. Orchestrates the hooks of the plugin.
	 * - Getbowtied_Presets_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-getbowtied-presets-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-getbowtied-presets-admin.php';

		$this->loader = new Getbowtied_Presets_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Getbowtied_Presets_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Getbowtied_Presets_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

$plugin = new Getbowtied_Presets();
$plugin->run();
