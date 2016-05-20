<?php
	if ( !current_user_can( 'manage_options' ) )  {
	        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	    }

	    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<div class="wrap">
<h2>Spanish Club Form Options</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'spanish-form-settings-group' ); ?>
    <?php do_settings_sections( 'spanish-form-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Form 1 price (Spanish lessons)</th>
        <td>$ <input type="text" name="spanish_lesson_price" value="<?php echo esc_attr( get_option('spanish_lesson_price') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Form 2 price (Dance lessons)</th>
        <td>$ <input type="text" name="dance_lesson_price" value="<?php echo esc_attr( get_option('dance_lesson_price') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Form 3 price (Memberships)</th>
        <td>$ <input type="text" name="membership_price" value="<?php echo esc_attr( get_option('membership_price') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Stripe Publishable API Key</th>
        <td><input type="text" name="stripe_pub_key" value="<?php echo esc_attr( get_option('stripe_pub_key') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Stripe Secret API Key</th>
        <td><input type="text" name="stripe_sec_key" value="<?php echo esc_attr( get_option('stripe_sec_key') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Paypal Sandbox Mode</th>
        <td><input name="paypal_mode" id="paypal_mode" type="checkbox" value="1" <?php echo checked( 1, get_option( 'paypal_mode' ), false ); ?> /> On</td>
        </tr>

        <tr valign="top">
        <th scope="row">Paypal API User</th>
        <td><input type="text" name="paypal_api_user" value="<?php echo esc_attr( get_option('paypal_api_user') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Paypal API Password</th>
        <td><input type="text" name="paypal_api_pass" value="<?php echo esc_attr( get_option('paypal_api_pass') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Paypal API Signature</th>
        <td><input type="text" name="paypal_api_sig" value="<?php echo esc_attr( get_option('paypal_api_sig') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

    <?php include plugin_dir_path( __FILE__ ) . '../../Excel/writeExcel.php'; ?>

</form>
</div>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
