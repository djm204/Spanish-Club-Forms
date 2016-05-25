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
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    include 'form-validations.php';
    $form_errors = 'Null';
    $form_errors_display = '';
    $stripe_status = '';

    if(isset($_POST['sc_name']) || isset($_GET['paypal']))
    {
        $form_errors = spanish_form_validation();
        $form_errors_display = $form_errors;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . "form_payment_record";

    if(isset($_POST['paymentType']) && $_POST['paymentType'] == "payInPerson" && $form_errors == '')
    {
        $price = '';

        if($_POST['item_type'] == 'membership')
        {
            $price = esc_attr( get_option('membership_price') );
        }
        else
        {
            echo '<div style="color:red"><p>No matching item found.</p></div>';
            exit();
        }

        $wpdb->query( $wpdb->prepare( 
            "
                INSERT INTO $table_name
                ( time, name, address, postal_code, ph_number, email, program, amount, payment_type, status )
                VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )
            ",
            current_time( 'mysql' ),
            $_POST['sc_name'],
            $_POST['sc_mailing_address'],
            $_POST['sc_postal_code'],
            $_POST['sc_ph_number'],
            $_POST['email'],
            $_POST['item_type'],
            $price,
            'In Person',
            'Pending'
        ) );

        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
        $userdata = array(
                        'user_login'  =>  $_POST['sc_username'],
                        'user_pass'   =>  $random_password,
                        'user_email'  =>  $_POST['email'],
                        'role' => ''
                    );
        wp_insert_user($userdata);
        $stripe_status .= 'Your user account has been made. Username and password has been sent to your e-mail. However, your account is inactive until you pay in person.';
        $email_message = 'Thank you for joining, ' . $_POST['sc_name'] . '. Your user name is ' . $_POST['sc_username'] . ' and your password is ' . $random_password . ' and it is suggested once you log in to change your password.';
        wp_mail( $_POST['email'], 'Thank you for joining', $email_message);

    }

    if(((isset($_GET['paypal']) && $_GET['paypal']=='checkout') && $form_errors == '' )|| 
        (isset($_GET['token']) && $_GET['token']!=''&& isset($_GET['PayerID']) && $_GET['PayerID']!='')
        )
    {
        include 'paypal/process.php';
        if((isset($_GET['token'])))
        {
            $form_errors_display = '';
        }
    }
    else
    {
    }

    $pull_form_atts = shortcode_atts( array(
        'form' => 'Please provide a form number'
    ), $atts );

    $stripe_key = esc_attr(get_option("stripe_pub_key"));

    if(isset($_POST['stripeToken']) && !empty($_POST['stripeToken']) && $form_errors == '')
    {

        \Stripe\Stripe::setApiKey(esc_attr(get_option("stripe_sec_key")));

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        $price = '';

        if($_POST['item_type'] == 'spanish_lessons')
        {
            $price = esc_attr( get_option('spanish_lesson_price') );
        }
        elseif($_POST['item_type'] == 'dance_lessons')
        {
            $price = esc_attr( get_option('dance_lesson_price') );
        }
        elseif($_POST['item_type'] == 'membership')
        {
            $price = esc_attr( get_option('membership_price') );
        }
        else
        {
            echo '<div style="color:red"><p>No matching item found.</p></div>';
            exit();
        }

        $price_replaced = str_replace(array('.', ','), '' , $price);

        try {
            
            
            $charge = \Stripe\Charge::create(array(
                "amount" => $price_replaced, // amount in cents, again
                "currency" => "cad",
                "source" => $token,
                "description" => "Example charge"
            ));

            if($charge['status'] == 'succeeded')
            {
                $wpdb->query( $wpdb->prepare( 
                    "
                        INSERT INTO $table_name
                        ( time, name, address, postal_code, ph_number, email, program, amount, payment_type, status )
                        VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )
                    ",
                    current_time( 'mysql' ),
                    $_POST['sc_name'],
                    $_POST['sc_mailing_address'],
                    $_POST['sc_postal_code'],
                    $_POST['sc_ph_number'],
                    $_POST['email'],
                    $_POST['item_type'],
                    $price,
                    'Stripe',
                    'Paid'
                ) );
                $stripe_status .= 'Payment Received!<br />';
                if($_POST['item_type'] == 'membership')
                {
                    $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                    wp_create_user( $_POST['sc_username'], $random_password, $_POST['email'] );
                    $stripe_status .= 'Your user account has been made. Username and password has been sent to your e-mail.';
                    $email_message = 'Thank you for joining, ' . $_POST['sc_name'] . '. Your user name is ' . $_POST['sc_username'] . ' and your password is ' . $random_password . ' and it is suggested once you log in to change your password.';
                    wp_mail( $_POST['email'], 'Thank you for joining', $email_message);
                }
            }
        } catch (\Stripe\Error\Card $e) {
            $form_errors_display .= '<div style="color:red"><p>Error in Stripe Payment: '.$e->getMessage().'</p></div>';
        }
    }
    $membership_username = '';
    if($pull_form_atts['form'] == '3')
    {
        $membership_username .= '<p>'.
        'Desired Username<br />'.
        '<input type="text" name="sc_username" value="" size="35" required/>'.
        '</p>';
    }

    $page_text = $form_errors_display.
	'<form action="'.str_replace('?paypal=checkout','',$_SERVER['REQUEST_URI']).'?paypal=checkout" method="post" id="paypal-payment-form">'.
	'<p>'.
    'Name<br />'.
    '<input type="text" id="sc_name" name="sc_name" pattern="[a-zA-Z0-9 ]+" value="" size="35" required/>'.
    '</p>'.
    $membership_username .
    '<p>'.
    'Mailing Address<br />'.
    '<input type="text" name="sc_mailing_address" value="" size="35" required/>'.
    '</p>'.
    '<p>'.
    'Postal Code<br />'.
    '<input type="text" name="sc_postal_code" pattern="[a-zA-Z0-9 ]+" value="" size="35" required/>'.
    '</p>'.
    '<p>'.
    'Phone Number<br />'.
    '<input type="tel" name="sc_ph_number" value="" size="35" required/>'.
    '</p>'.
    '<p>'.
    'Email<br />'.
    '<input type="email" name="email" value="" size="35" required/>'.
    '</p>'.
    '<p>'.
    'Payment<br />'.
    ''.form_values($pull_form_atts['form']).
    '<input type="radio" name="paymentType" id="payPayPal" checked="checked"> Paypal</input><br />'.
    '<input type="radio" name="paymentType" id="payStripe"> Stripe</input><br />'.
    '</p>'.
    '<div id="stripe-details" style="display:none">
          <div class="form-row">
            <label>
              <span>Card Number</span>
              <input type="text" size="20" data-stripe="number">
            </label>
          </div>

          <div class="form-row">
            <label>
              <span>Expiration (MM/YY)</span>
              <input type="text" size="2" data-stripe="exp_month">
            </label>
            <span> / </span>
            <input type="text" size="2" data-stripe="exp_year">
          </div>

          <div class="form-row">
            <label>
              <span>CVC</span>
              <input type="text" size="4" data-stripe="cvc">
            </label>
          </div>
          </div>'.
          '<span class="payment-errors" style="color:red"></span>'.
    '<p><input type="submit" class="submit" name="cf-submitted" value="Send"></p>'.

    '</form>'.
    '<div>'.$stripe_status.'</div>'.
    '<script type="text/javascript" src="https://js.stripe.com/v2/"></script>'.
    '<script type="text/javascript">
            Stripe.setPublishableKey("'.$stripe_key.'");
            jQuery(function($) {
              var $paypal_submit_value = $("#paypal-payment-form").attr("action");

              $("#payPayPal").click(function () {
                    if ($(this).is(":checked")) {
                        $("#payment-form").attr("action", $paypal_submit_value);
                        $("#payment-form").attr("id", "paypal-payment-form");
                        $("#in-person-payment-form").attr("action", $paypal_submit_value);
                        $("#in-person-payment-form").attr("id", "paypal-payment-form");
                        $("#stripe-details").hide();
                    }
                });

                $("#payStripe").click(function () {
                    if ($(this).is(":checked")) {
                        $("#paypal-payment-form").attr("action", "");
                        $("#paypal-payment-form").attr("id", "payment-form");
                        $("#in-person-payment-form").attr("action", "");
                        $("#in-person-payment-form").attr("id", "payment-form");
                        $("#stripe-details").show();

                        $("#payment-form").submit(function(event) {
                            var $form = $("#payment-form");
                            // Disable the submit button to prevent repeated clicks:
                            $form.find(".submit").prop("disabled", true);

                            // Request a token from Stripe:
                            Stripe.card.createToken($form, stripeResponseHandler);

                            // Prevent the form from being submitted:
                            return false;
                          });
                    }
                });

                $("#payInPerson").click(function () {
                    if ($(this).is(":checked")) {
                        $("#payment-form").attr("action", "");
                        $("#payment-form").attr("id", "in-person-payment-form");
                        $("#paypal-payment-form").attr("action", "");
                        $("#paypal-payment-form").attr("id", "in-person-payment-form");
                        $("#stripe-details").hide();
                    }
                });

                function stripeResponseHandler(status, response) {
                  // Grab the form:
                  var $form = $("#payment-form");

                  if (response.error) { // Problem!

                    // Show the errors on the form:
                    $form.find(".payment-errors").text(response.error.message);
                    $form.find(".submit").prop("disabled", false); // Re-enable submission

                  } else { // Token was created!

                    // Get the token ID:
                    var token = response.id;

                    // Insert the token ID into the form so it gets submitted to the server:
                    $form.append($("' . "<input type='hidden' name='stripeToken'>" . '").val(token));

                    // Submit the form:
                    $form.get(0).submit();
                  }
                };
            });
          </script>';

          return $page_text;
}

function form_values($form_number)
{
    switch($form_number)
    {
        case '1':
            return '<div><p>$'.esc_attr( get_option('spanish_lesson_price') ).'</p></div><input id="item_type" name="item_type" value="spanish_lessons" type="hidden"></input>';
            break;
        case '2':
            return '<div><p>$'.esc_attr( get_option('dance_lesson_price') ).'</p></div><input id="item_type" name="item_type" value="dance_lessons" type="hidden"></input>';
            break;
        case '3':
            return '<div><p>$'.esc_attr( get_option('membership_price') ).'</p></div><input id="item_type" name="item_type" value="membership" type="hidden"></input><input type="radio" name="paymentType" id="payInPerson" value="payInPerson"> Pay In Person</input><br />';
            break;
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
    add_action( 'admin_menu', 'spanish_club_excel_admin_menu' );

    //call register settings function
    add_action( 'admin_init', 'spanish_form_plugin_settings' );

    if ( ! class_exists( 'Stripe\Stripe' ) ) 
    {
        require_once( plugin_dir_path( __FILE__ ) . 'libraries/stripe-php/init.php' );
    }
}


function spanish_form_plugin_settings() {
    //register our settings
    register_setting( 'spanish-form-settings-group', 'spanish_lesson_price' );
    register_setting( 'spanish-form-settings-group', 'dance_lesson_price' );
    register_setting( 'spanish-form-settings-group', 'membership_price' );
    register_setting( 'spanish-form-settings-group', 'stripe_pub_key' );
    register_setting( 'spanish-form-settings-group', 'stripe_sec_key' );
    register_setting( 'spanish-form-settings-group', 'paypal_mode' );
    register_setting( 'spanish-form-settings-group', 'paypal_api_user' );
    register_setting( 'spanish-form-settings-group', 'paypal_api_pass' );
    register_setting( 'spanish-form-settings-group', 'paypal_api_sig' );
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

function spanish_club_excel_admin_menu() {
    add_menu_page(
        'Spanish Club Excel',
        'Spanish Club Excel',
        'manage_options',
        'spanish-club-excel',
        'excel_options_page'
    );
}

function wp_options_page() {
    include 'admin/partials/spanish-club-forms-admin-display.php';
}

function excel_options_page() {
    echo '<br /><a href="writeExcel.xlsx" >Download Payments Excel Sheet</a><br /><br /><a href="writeInPerson.xlsx" >Download Pay in Person Excel Sheet</a>';
}

add_action('init', 'myStartSession', 1);

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

add_action('template_redirect','yoursite_template_redirect');
function yoursite_template_redirect() {
  if ($_SERVER['REQUEST_URI']=='/wp-admin/writeExcel.xlsx') {
    include 'Excel/writeExcel.php';

    header("Content-type: application/x-msdownload",true,200);
    header("Content-Disposition: attachment; filename=SpanishClubPayments.xlsx");
    header("Pragma: no-cache");
    header("Expires: 0");
    $objWriter->save('php://output');
    exit();
  }

  if ($_SERVER['REQUEST_URI']=='/wp-admin/writeInPerson.xlsx') {
    include 'Excel/writeExcelInPerson.php';

    header("Content-type: application/x-msdownload",true,200);
    header("Content-Disposition: attachment; filename=SpanishClubInPersonPayments.xlsx");
    header("Pragma: no-cache");
    header("Expires: 0");
    $objWriter->save('php://output');
    exit();
  }
}


run_spanish_club_forms();
