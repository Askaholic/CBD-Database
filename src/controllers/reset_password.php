<?php

/* reset_password.php
 * Aisha Peters
 * Created: March 20, 2018
 *
 * Allows a user to reset their password if accessed with valid token.
 */

require_once( DP_PLUGIN_DIR . 'class.passwordhash.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );
require_once( DP_PLUGIN_DIR . 'models/tokens.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );

$nonce_name = 'reset_nonce';
if ( isset( $_POST[$nonce_name] ) && !wp_verify_nonce( $_POST[$nonce_name], 'submit' ) ) {
	die( 'Bad token' );
}

/* Check if token is set */
if (!isset($_GET['q']) || $_GET['q'] == NULL){
	die( 'Bad access path.' );
}

/* Check if token is valid */
$token = $_GET['q'];
$result = Token::query_from_token( $token );
if ( count( $result ) == 0 ) {
		die( "Incorrect link or password already changed." );
}
		
$error = '';
$info = '';

if ( isset( $_POST[$nonce_name] ) ) {
    try {
		/* Check if email is valid */
		$email = valid_email( not_empty( $_POST['email'] ) );
    	$user = User::query_users_from_email( $email );
        if ( count( $user ) == 0 ) {
            throw new BadInputException( "Incorrect or unregistered email address." );
        }
        
        /* Check if ID matches */
		$id = $result[0]->user_id;
		$request_id = $user[0]->id;
		if ( $request_id != $id ) {
			throw new BadInputException( "Data mismatch. Please submit a new reset request." );
		}
    
    	/* Retrieve new password and replace old */
		$newpass =  valid_password( not_empty( $_POST['new_password'] ) );
		$newpass2 = not_empty( $_POST['confirm_password'] );

		if ( $newpass !== $newpass2 ) {
			throw new BadInputException( "Passwords do not match" );
		}

		$hash = Password::hash( $newpass );
		
        $user = User::query_users_from_email( $email );
        if ( count( $user ) == 0 ) {
            throw new BadInputException( "Email address not registered to an account." );
        }
		
		$user[0]->update('password', $hash, 'id', $id );

        $info = "Your password has been updated.";
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
