<?php

/* forgot_password.php
 * Aisha Peters
 * Created: March 20, 2018
 *
 * Allows a user to request a password reset.
 * Request is sent to registered email.
 */
 
require_once( DP_PLUGIN_DIR . 'models/user.php' );

$nonce_name = 'forgot_nonce';
if ( isset( $_POST[$nonce_name] ) && !wp_verify_nonce( $_POST[$nonce_name], 'submit' ) ) {
	die( 'Bad token' );
}

$error = '';
$info = '';

if ( isset( $_POST[$nonce_name] ) ) {
    try {
    
    	/* Check to see if a user exists with this email */
    	$email = valid_email( not_empty( $_POST['email'] ) );
    	$user = User::query_user_from_email( $email );
        if ( empty( $user )) {
            throw new BadInputException( "No user with that email address exists." );
        }
		
		/* Create a unique user password reset token */
		$length = 78;
		$token = array(
				'value' = bin2hex(random_bytes($length))
				'expires' = $expiry
				)
				
		// Create a reset link
		// TODO: Use actual links in code
		$pwrurl = "www.yoursitehere.com/reset_password.php?q=".$token[value];
		
		// Mail the token
		$mailbody = "Dear user,\n\nIf this email does not apply to you please ignore it. ";
		$mailbody .= "It appears that you have requested a password reset at our website www.yoursitehere.com\n\n";
		$mailbody .= "To reset your password, please click the link below. ";
		$mailbody .= "If you cannot click it, please paste it into your web browser's address bar.\n\n";
		$mailbody .= $pwrurl . "\n\nThanks,\nThe Administration";
		mail($user->email, "www.yoursitehere.com - Password Reset", $mailbody);
		
        $info = "A password recovery token has been sent to your email address.";
    }
    catch ( Exception $e ) {
        if ( get_class( $e ) !== BadInputException ) {
            error_log($e);
        }

        $error = $e->getMessage();

        if ( get_class( $e ) === PDOException ) {
            $error = "Database error";
        }
    }
}

DanceParty::render_view_with_template( 'forgot_password.php',
    array(
        'error' => $error,
        'info' => $info
    )
);
		
?>
