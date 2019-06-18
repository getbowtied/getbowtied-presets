<?php
	/**
	 * Plugin Name: GetBowtied Presets
	 * Plugin URI: https://getbowtied.com/
	 * Description: A suite of tools to help you kickstart your GetBowtied theme.
	 * Version: 1.0
	 * Author: GetBowtied
	 * Author URI: https://getbowtied.com
	 * Requires at least: 4.9
	 * Tested up to: 4.9
	 *
	 * @package  GetBowtied Presets
	 * @author GetBowtied
	 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action('admin_menu', 'getbowtied_presets_menu');
 
function getbowtied_presets_menu(){
    add_menu_page( 'GetBowtied Presets', 'GetBowtied Presets', 'manage_options', 'getbowtied-presets', 'getbowtied_presets_main' );
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

add_action( 'admin_enqueue_scripts', 'load_styles' );
add_action( 'admin_enqueue_scripts', 'load_scripts' );

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

add_action( 'after_setup_theme', 'getbowtied_presets_redirect_url');