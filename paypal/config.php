<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
  //start session in all pages
  //if (session_status() == PHP_SESSION_NONE) { session_start(); } //PHP >= 5.4.0
	$mode='';
	// sandbox or live
	if ( checked( 1, get_option( 'paypal_mode' ), false )!= '' )
	{
		$mode = 'sandbox';
	}
	define('PPL_MODE', $mode);


	define('PPL_API_USER', esc_attr( get_option('paypal_api_user')));
	define('PPL_API_PASSWORD', esc_attr( get_option('paypal_api_pass')));
	define('PPL_API_SIGNATURE', esc_attr( get_option('paypal_api_sig')));

	
	define('PPL_LANG', 'EN');
	
	define('PPL_LOGO_IMG', '');
	
	define('PPL_RETURN_URL', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
	define('PPL_CANCEL_URL', "http://$_SERVER[HTTP_HOST]");

	define('PPL_CURRENCY_CODE', 'CAD');
?>