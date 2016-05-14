<?php
	if ( !current_user_can( 'manage_options' ) )  {
	        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	    }
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
        <th scope="row">Paypal API Key</th>
        <td><input type="text" name="paypal_api_key" value="<?php echo esc_attr( get_option('paypal_api_key') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Stripe API Key</th>
        <td><input type="text" name="stripe_api_key" value="<?php echo esc_attr( get_option('stripe_api_key') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>