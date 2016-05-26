<?php

    function sendMail($email, $name, $username, $password)
    {
        $wp_admin_page = get_admin_url();
        $email_message = "Thank you for joining, " . $name . ". Here are your login credentials." .
                         "\n\nUser Name: " . $username . 
                         "\nPassword: " . $password . 
                         "\n\nYou can log in at " . $wp_admin_page . " and it is suggested once you log in to change your password.";
        wp_mail( $email, 'Thank you for joining', $email_message);
    }
?>