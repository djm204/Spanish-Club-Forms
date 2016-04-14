<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              not available
 * @since             1.0.0
 * @package           Spanish_Club_Forms
 *
 * @wordpress-plugin
 * Plugin Name:       Spanish Club Forms
 * Plugin URI:        http://wordpresstest-tpascal.rhcloud.com/become-a-member/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Thomas Pascal
 * Author URI:        not available
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spanish-club-forms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-spanish-club-forms-activator.php
 */
function activate_spanish_club_forms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spanish-club-forms-activator.php';
	Spanish_Club_Forms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-spanish-club-forms-deactivator.php
 */
function deactivate_spanish_club_forms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spanish-club-forms-deactivator.php';
	Spanish_Club_Forms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_spanish_club_forms' );
register_deactivation_hook( __FILE__, 'deactivate_spanish_club_forms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-spanish-club-forms.php';

/**
* Code for form 1
*/
function spanish_classes_form( $atts ){
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
    echo 'Name<br />';
    echo '<input type="text" name="name" pattern="[a-zA-Z0-9 ]+" value="" size="35" />';
    echo '</p>';
    echo '<p>';
    echo 'Mailing Address<br />';
    echo '<input type="text" name="mailing_address" pattern="[a-zA-Z0-9 ]+" value="" size="35" />';
    echo '</p>';
    echo '<p>';
    echo 'Postal Code<br />';
    echo '<input type="text" name="postal_code" pattern="[a-zA-Z0-9 ]+" value="" size="35" />';
    echo '</p>';
    echo '<p>';
    echo 'Phone Number<br />';
    echo '<input type="tel" name="ph_number" value="" size="35" />';
    echo '</p>';
    echo '<p>';
    echo 'Email<br />';
    echo '<input type="email" name="email" value="" size="35" />';
    echo '</p>';
    echo '</form>';
}

function deliver_mail() {

    // if the submit button is clicked, send the email
    if ( isset( $_POST['sc-submitted'] ) ) {

        // sanitize form values
        $name    = sanitize_text_field( $_POST["sc-name"] );
        $email   = sanitize_email( $_POST["sc-email"] );

        // get the blog administrator's email address
        $to = get_option( 'admin_email' );
        $subject = "Hello";
        $message= "Test";

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_spanish_club_forms() {

	$plugin = new Spanish_Club_Forms();
	$plugin->run();

	//Adds form 1 shortcode
	add_shortcode( 'sc_form1', 'spanish_classes_form' );
}
run_spanish_club_forms();
