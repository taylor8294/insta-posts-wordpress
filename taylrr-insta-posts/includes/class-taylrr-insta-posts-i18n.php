<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://taylrr.co.uk/
 * @since      1.0.0
 *
 * @package    Taylrr_Insta_Posts
 * @subpackage Taylrr_Insta_Posts/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Taylrr_Insta_Posts
 * @subpackage Taylrr_Insta_Posts/includes
 * @author     Alex Taylor <alex@taylrr.co.uk>
 */
class Taylrr_Insta_Posts_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'taylrr-insta-posts',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
