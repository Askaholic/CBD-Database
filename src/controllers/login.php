<?php

require_once(DP_PLUGIN_DIR . 'class.passwordhash.php');
require_once(DP_PLUGIN_DIR . 'models/user.php');

if(isset($_POST['login_nonce']) && !wp_verify_nonce($_POST['login_nonce'], 'submit'))
{
    die( 'Bad token' );
}

if ( isset( $_POST['login_nonce'] ) ) {
    $members = User::query_all_without_membership();
    $email = $_POST['email'];
    $member = null;
    foreach($members as $usr) {
        if ($email == $usr->email) {
            $member = $usr;
            break;
        }
    }
    $password_hash = $member->password;
    $password = $_POST['password'];

    if ( !empty( $email ) && !empty( $password ) ) {
        print_r($member->email);
        if ( $email == $member->email) {
            Password::verify($password, $password_hash);        }
    }
}

DanceParty::render_view_with_template( 'login.php' );

?>