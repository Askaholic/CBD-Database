<?php

/* forgot_password.php
 * Aisha Peters
 * Created: March 20, 2018
 *
 * Allows a user to request a password reset.
 * Request is sent to registered email.
 */
 
require_once( DP_PLUGIN_DIR . 'models/user.php' );
require_once( DP_PLUGIN_DIR . 'models/tokens.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );

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
    	$user = User::query_users_from_email( $email );
        if ( count( $user ) == 0 ) {
            throw new BadInputException( "Incorrect or unregistered email address." );
        }
		
		/* Create a unique user password reset token */
		$length = 32;
		$timespan = 15; // minutes till expiry
		$token = 123;//bin2hex(random_bytes($length)); PHP 7 ONLY, NEED random_compat LIBRARY
		$expires = new DateTime('NOW');
		$expires->add(new DateInterval('PT'.$timespan.'M'));
		$expiration_date = $expires->format('Y-m-d H:i:s');
		
		$id = $user[0]->id;
		$email = $user[0]->email;
		
		$result = Token::query_from_id( $id );
		if ( count( $result ) == 0 ) {
            $recovery_token = new Token( array(
            'user_id' => $id,
            'recovery_token' => $token,
            'expiration_date' => $expiration_date
        	));
        	$recovery_token->commit();
        }
        else{
        	$user[0]->recovery_token = $token;
        	$user[0]->expiration_date = $expiration_date;
			$user[0]->commit();
        }
		
		
		/*		
		// Create a reset link
		$uri = 'http://' .$_SERVER['HTTP_HOST'];
		$pwurl = $uri. '/reset_password.php?q=' .$token;
		
		// Send the link to user email
		$to = $user[0]->email;
		$message = "Dear user,\n\nIf this email does not apply to you please ignore it. ";
		$message .= "It appears that you have requested a password reset at contraborealis.org.\n\n";
		$message .= "To reset your password, please click the link below. ";
		$message .= "If you cannot click it, please paste it into your web browser's address bar.\n\n";
		$message .= $pwurl . "\n\nThis link will expire in " .$expiry. " minutes.";
		mail($to, "Contra Borealis - Password Reset", $message);*/
		
        $info = "Request recieved. Please check your email.";
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
