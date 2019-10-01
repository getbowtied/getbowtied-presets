<?php
	/**
	 * Plugin Name: GetBowtied Presets
	 * Plugin URI: https://getbowtied.com/
	 * Description: A suite of tools to help you kickstart your GetBowtied theme.
	 * Version: 1.1
	 * Author: GetBowtied
	 * Author URI: https://getbowtied.com
	 * Requires at least: 5.2.0
	 * Tested up to: 5.2.1
	 *
	 * @package  GetBowtied Presets
	 * @author GetBowtied
	 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Plugin Updater
require 'core/updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/getbowtied/getbowtied-presets/master/core/updater/assets/plugin.json',
	__FILE__,
	'getbowtied-presets'
);

class GetBowtied_Presets {
	
	function __construct() {
		add_action('admin_menu', array($this, 'getbowtied_presets_menu'));
		add_action( 'admin_enqueue_scripts', array($this, 'load_styles' ));
		add_action( 'admin_enqueue_scripts', array($this, 'load_scripts' ));
		add_action( 'after_setup_theme', array($this, 'getbowtied_presets_redirect_url'));
	}
	
	 
	function getbowtied_presets_menu(){
	    add_menu_page( 'GetBowtied Presets', 'Presets', 'manage_options', 'getbowtied-presets', array($this,'getbowtied_presets_main'));
	}
	 
	function getbowtied_presets_main(){
		if (isset($_GET['changeset_id']) && is_numeric($_GET['changeset_id']))
			include dirname( __FILE__ ) . "/includes/templates/single.php";
		else
			include dirname( __FILE__ ) . "/includes/templates/main.php";
	}

	function load_styles() {
		wp_enqueue_style( 'getbowtied-presets-css', plugins_url( 'assets/css/styles.css', __FILE__ ), array(), '1.0' );
	}

	function load_scripts() {
		wp_enqueue_script( 'getbowtied-presets-js', plugins_url( 'assets/js/scripts-dist.js', __FILE__ ), array(), '1.0' );
	}

	function getbowtied_presets_redirect_url() {
		if (isset($_REQUEST['customize_changeset_uuid_gbt'])) {
			$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$parsed = explode('?', $current_url);
			$parsed[1]= 'customize_changeset_uuid=' . $_GET['customize_changeset_uuid_gbt'];
			$parsed = implode($parsed, '?');
			wp_redirect( $parsed );
			exit();
		}
	}
}

new GetBowtied_Presets;
