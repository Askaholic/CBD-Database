<?php

/* reset_password.php
 * Aisha Peters
 * Created: March 20, 2018
 *
 * Allows a user to reset their password if accessed with valid token.
 */

require_once( DP_PLUGIN_DIR . 'class.passwordhash.php' );
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );

$nonce_name = 'reset_nonce';
if ( isset( $_POST[$nonce_name] ) && !wp_verify_nonce( $_POST[$nonce_name], 'submit' ) ) {
	die( 'Bad token' );
}

$error = '';
$info = '';

if ( isset( $_POST[$nonce_name] ) ) {
    try {
    	$token = isset($_GET['q']) ? $_GET['q'] : null;
    	//TODO: check for token match in database before allowing change
    	$email = null; // TODO: get email from database, stored with token
    
		$newpass =  valid_password( not_empty( $_POST['new_password'] ) );
		$newpass2 = not_empty( $_POST['confirm_password'] );

		if ( $newpass !== $newpass2 ) {
			throw new BadInputException( "Passwords do not match" );
		}

		$hash = Password::hash( $newpass );
		
    	$user = User::query_user_from_email( $email );
        if ( empty( $user )) {
            throw new BadInputException( "Email address not registered to an account." );
        }
		$user->password = $newpass;
		$user->commit();

        $info = "Change succesful. Please login with your new password.";
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

DanceParty::render_view_with_template( 'reset_password.php',
    array(
        'error' => $error,
        'info' => $info
    )
);

?>
