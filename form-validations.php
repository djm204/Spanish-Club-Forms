<?php

	function spanish_form_validation()
	{
		$return_errors = '';
		if(isset($_POST['sc_name']) && $_POST['sc_name'] != '')
		{
			$_POST['sc_name'] = sanitize_text_field($_POST['sc_name']);
		}
		else
		{
			$return_errors .= '<div style="color:red"><p>Did not enter Name</p></div>';
		}
		if(isset($_POST['sc_mailing_address']) && $_POST['sc_mailing_address'] != '')
		{
			$_POST['sc_mailing_address'] = sanitize_text_field($_POST['sc_mailing_address']);
		}
		else
		{
			$return_errors .= '<div style="color:red"><p>Did not enter Address</p></div>';
		}
		if(isset($_POST['sc_postal_code']) && $_POST['sc_postal_code'] != '')
		{
			$_POST['sc_postal_code'] = sanitize_text_field($_POST['sc_postal_code']);
		}
		else
		{
			$return_errors .= '<div style="color:red"><p>Did not enter Postal Code</p></div>';
		}
		if(isset($_POST['sc_ph_number']) && $_POST['sc_ph_number'] != '')
		{
			$_POST['sc_ph_number'] = sanitize_text_field($_POST['sc_ph_number']);
		}
		else
		{
			$return_errors .= '<div style="color:red"><p>Did not enter Phone Number</p></div>';
		}
		if(isset($_POST['email']) && $_POST['email'] != '')
		{
			$_POST['email'] = sanitize_email($_POST['email']);
		}
		else
		{
			$return_errors .= '<div style="color:red"><p>Did not enter E-Mail</p></div>';
		}
		if(isset($_POST['item_type']) && $_POST['item_type'] != '')
		{
			$_POST['item_type'] = sanitize_text_field($_POST['item_type']);
		}
		else
		{
			$return_errors .= '<div style="color:red"><p>Item Type is missing</p></div>';
		}
		if(isset($_POST['item_type']) && $_POST['item_type'] == 'membership')
		{
			if(isset($_POST['sc_username']) && $_POST['sc_username'] != '')
			{
				$_POST['sc_username'] = sanitize_text_field($_POST['sc_username']);
				$user_id = username_exists( $_POST['sc_username'] );
				if ( !$user_id && email_exists($_POST['email']) == false ) {
				} else {
					$return_errors .= '<div style="color:red"><p>Username or Email already exists</p></div>';
				}
			}
			else
			{
				$return_errors .= '<div style="color:red"><p>Did not enter Username</p></div>';
			}
		}

		return $return_errors;
	}

?>