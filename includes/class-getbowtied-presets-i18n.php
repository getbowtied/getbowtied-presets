<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://getbowtied.com/
 * @since      1.0.0
 *
 * @package    Getbowtied_Presets
 * @subpackage Getbowtied_Presets/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Getbowtied_Presets
 * @subpackage Getbowtied_Presets/includes
 * @author     GetBowtied <vanesa@getbowtied.com>
 */
class Getbowtied_Presets_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'getbowtied-presets',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
