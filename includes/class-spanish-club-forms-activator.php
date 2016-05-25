<?php

/**
 * Fired during plugin activation
 *
 * @link       not available
 * @since      1.0.0
 *
 * @package    Spanish_Club_Forms
 * @subpackage Spanish_Club_Forms/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Spanish_Club_Forms
 * @subpackage Spanish_Club_Forms/includes
 * @author     Thomas Pascal <ashtom@mymts.net>
 */
class Spanish_Club_Forms_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

   		$table_name = $wpdb->prefix . "form_payment_record";

   		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  name varchar(45) NOT NULL,
		  address varchar(65) NOT NULL,
		  postal_code varchar(9) NOT NULL,
		  ph_number varchar(15) NOT NULL,
		  email varchar(45) NOT NULL,
		  program varchar(40) NOT NULL,
		  amount varchar(12) NOT NULL,
		  payment_type varchar(22) NOT NULL,
		  status varchar(22) NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

}
