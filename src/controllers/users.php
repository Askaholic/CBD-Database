<?php

require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );

if ( ! Authenticate::is_logged_in() ) {
    wp_redirect(get_page_link(get_page_by_path('login')) . '?afterlog=users');
}

if ( ! Authenticate::is_admin() ) {
    die( 'Unauthorized' );
}

if ( isset( $_POST['renew_member_nonce'] ) && !wp_verify_nonce( $_POST['renew_member_nonce'], 'submit' ) ) {
    die( 'Bad token' );
}

$error;
$info;

if ( isset( $_POST['renew_member_nonce'] ) ) {
    try {
        $id = valid_id(not_empty($_POST['id']));
        $expiry = valid_date(not_empty($_POST['expiry']));

        // Check that the user actually exists
        try {
            $usr = new User(
                array(
                    'id' => $id
                )
            );
            $usr->pull();
        } catch ( Exception $e ) {
            throw new BadInputException("User with id $id does not exist");
        }

        $memberData = array(
            'user_id' => $id,
            'expiration_date' => $expiry
        );
        $member = new Membership( $memberData );
        $member->commit();

    } catch ( Exception $e ) {
        if ( get_class( $e ) !== BadInputException ) {
            error_log($e);
        }

        $error = $e->getMessage();

        if ( get_class($e) === PDOException) {
            $error = "Database error";
        }
    }
}

$members = User::query_all_users();

DanceParty::render_view_with_template( 'users.php',
    array(
        'members' => $members,
        'error' => $error,
        'info' => $info
    )
);

?>
