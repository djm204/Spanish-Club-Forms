<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       not available
 * @since      1.0.0
 *
 * @package    Spanish_Club_Forms
 * @subpackage Spanish_Club_Forms/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Spanish_Club_Forms
 * @subpackage Spanish_Club_Forms/includes
 * @author     Thomas Pascal <ashtom@mymts.net>
 */
class Spanish_Club_Forms_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'spanish-club-forms',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
