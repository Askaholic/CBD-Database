<?php

require_once( DP_PLUGIN_DIR . 'class.passwordhash.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );

if( isset( $_POST['login_nonce'] ) && !wp_verify_nonce( $_POST['login_nonce'], 'submit' ) ) {
    die( 'Bad token' );
}

if ( isset( $_POST['login_nonce'] ) ) {
    $email = $_POST['email'];
    
    $usr = User::query_user_from_email($email);
    if ( count( $usr ) === 0 ) {
        throw new BadInputException( "$email does not have an associated account" );
        // TODO
    }
    $password_hash = $usr[0]->password;
    $password = $_POST['password'];

    if ( empty( $email ) || empty( $password ) ) {
        die( 'Field empty');
    }
    if ( !Password::verify($password, $password_hash) ) {
        die( 'Invalid Password!' );
    }

    $_SESSION['user'] = $usr[0]->role_id;
    //$GLOBALS['session']->set( 'user', $usr[0] );
}

DanceParty::render_view_with_template( 'login.php' );

?>
