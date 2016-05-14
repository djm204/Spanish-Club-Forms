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
* Code for shared form components
*/
function spanish_form_general( $atts ){
    if(isset($POST_['sc_name']) && $POST_['sc_name'] != '')
    {

    }

    $pull_form_atts = shortcode_atts( array(
        'form' => 'Please provide a form number'
    ), $atts );

    echo '<form action="" method="post">';
    echo '<p>';
    echo 'Name<br />';
    echo '<input type="text" name="sc_name" pattern="[a-zA-Z0-9 ]+" value="" size="35" />';
    echo '</p>';
    echo '<p>';
    echo 'Mailing Address<br />';
    echo '<input type="text" name="sc_mailing_address" pattern="[a-zA-Z0-9 ]+" value="" size="35" />';
    echo '</p>';
    echo '<p>';
    echo 'Postal Code<br />';
    echo '<input type="text" name="sc_postal_code" pattern="[a-zA-Z0-9 ]+" value="" size="35" />';
    echo '</p>';
    echo '<p>';
    echo 'Phone Number<br />';
    echo '<input type="tel" name="sc_ph_number" value="" size="35" />';
    echo '</p>';
    echo '<p>';
    echo 'Email<br />';
    echo '<input type="email" name="email" value="" size="35" />';
    echo '</p>';
    echo '<p>';
    echo 'Payment<br />';
    echo ''.form_values($pull_form_atts['form']);
    echo '</p>';
    echo '<p><input type="submit" name="cf-submitted" value="Send"></p>';

    echo '</form>';
}

function form_values($form_number)
{
    switch($form_number)
    {
        case '1':
            echo '<div><p>$'.esc_attr( get_option('spanish_lesson_price') ).'</p></div>'.
                '<input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="LXZYPZRGEZM26">
                <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">';
            break;
        case '2':
            echo "<div><p>$9.99</p></div>";
            break;
        case '3':
            echo "<div><p>$39.99</p></div>";
            break;
    }
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
    add_shortcode( 'sc_form', 'spanish_form_general' );

    add_action( 'admin_menu', 'spanish_club_forms_admin_menu' );

    //call register settings function
    add_action( 'admin_init', 'spanish_form_plugin_settings' );
}


function spanish_form_plugin_settings() {
    //register our settings
    register_setting( 'spanish-form-settings-group', 'spanish_lesson_price' );
    register_setting( 'spanish-form-settings-group', 'dance_lesson_price' );
    register_setting( 'spanish-form-settings-group', 'membership_price' );
    register_setting( 'spanish-form-settings-group', 'paypal_api_key' );
    register_setting( 'spanish-form-settings-group', 'stripe_api_key' );
}


function spanish_club_forms_admin_menu() {
    add_menu_page(
        'Spanish Club Forms',
        'Spanish Club Forms',
        'manage_options',
        'spanish-club-forms',
        'wp_options_page'
    );
}

function wp_options_page() {
    include 'admin/partials/spanish-club-forms-admin-display.php';
}


run_spanish_club_forms();
