<?php

require_once( DP_PLUGIN_DIR . 'class.passwordhash.php' );
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );

if ( ! Authenticate::logged_in() ) {
    // TODO: Redirect to login page
}

if( isset( $_POST['password_nonce'] ) && !wp_verify_nonce( $_POST['password_nonce'], 'submit' ) ) {
    die( 'Bad token' );
}

if ( isset( $_POST['login_nonce'] ) ) {

	$pass =  valid_password( not_empty( $_POST['new_password'] ) );
        $pass2 = not_empty( $_POST['confirm_password'] );

        if ( $pass !== $pass2 ) {
            throw new BadInputException( "Passwords do not match" );
        }

	$hash = Password::hash( $pass );

	$user = $GLOBALS['session']->get( 'user' );
	$user->password = $pass;
	$user->commit();

}

DanceParty::render_view_with_template( 'reset_password.php' );