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
		$timespan = 15; // minutes till expiry
		$token = bin2hex(openssl_random_pseudo_bytes(Token::LENGTH));
		$expires = new DateTime('NOW');
		$expires->add(new DateInterval('PT' . $timespan . 'M'));
		$expiration_date = $expires->format('Y-m-d H:i:s');
		
		$id = $user[0]->id;
		$first = $user[0]->first_name;
		$email = $user[0]->email;
		
		// insert token into database (updates on duplicate reset request)
        $recovery_token = new Token( array(
        'user_id' => $id,
        'recovery_token' => $token,
        'expiration_date' => $expiration_date
    	));
    	$recovery_token->commit();

		$link = get_page_link(get_page_by_title('reset password')) . '?token=' . $token;
        $subject = "Password Reset";
        $body = "Hello $first, <br><br>";
        $body .= "You recently requested to reset your Contraborealis password. ";
        $body .= "Click the link below to reset your password. <br><br>";
        $body .= "<a href=$link> Reset password</a><br><br>";
		$body .= "This link will expire in $timespan minutes.";
        send_email($email, $subject, $body);
		
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
