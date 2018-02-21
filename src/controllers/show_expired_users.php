<?php

require_once( DP_PLUGIN_DIR . 'models/user.php' );

// TODO: Authenticate that the user is an admin

if ( !wp_verify_nonce( $_POST['renew_member_nonce'], 'submit' ) ) {
    die( 'Bad token' );
}


if ( isset( $_POST['renew_member_nonce'] ) ) {
    $id = $_POST['id'];
    $expiry = $_POST['expiry'];

    if ( !empty( $id ) && !empty( $expiry ) ) {
        $memberData = array(
            'user_id' => $id,
            'expiration_date' => $expiry
        );
        $member = new Membership( $memberData );
        $member->commit();
    }
}


$members = User::query_all_without_membership();

DanceParty::render_view_with_template( 'show_expired_users.php', array('members' => $members) );

?>
