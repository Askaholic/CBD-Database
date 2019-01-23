<?php
/* members.php
 * Rohan Weeden
 * Created: March 12 , 2018
 */
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );

if ( ! Authenticate::is_logged_in() ) {
    wp_redirect(get_page_link(get_page_by_path('login')) . '?afterlog=members');
}

if ( ! Authenticate::is_door_host() ) {
    die( 'Unauthorized' );
}

if ( isset( $_POST['change_role_nonce'] ) && !wp_verify_nonce( $_POST['change_role_nonce'], 'submit' ) ) {
    die( 'Bad token' );
}

$error;
$info;

if ( isset( $_POST['change_role_nonce'] ) ) {
    try {
        $email = valid_email( not_empty( $_POST['email'] ) );
        $role = not_empty( $_POST['role'] );

        $user = User::query_users_from_email( $email );

        if ( count( $user ) == 0 ) {
            throw new BadInputException( "Incorrect or unregistered email address." );
        }

        $user[0]->update('role_id', $role, 'email', $email );

        $info = "Role changed successfully.";
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

$title = 'Active Member List';
$error;
$info;
$members = array();

try {
    $members = User::query_all_with_membership();
}
catch (Exception $e) {
    if ( get_class( $e ) !== BadInputException ) {
        error_log($e);
    }
    /*
     * Display an error message if something goes wrong
     */
    $error = $e->getMessage();

    if ( get_class($e) === PDOException) {
        $error = "Database error";
    }
}

DanceParty::render_view_with_template( 'members.php',
    array(
        'title' => $title,
        'members' => $members,
        'error' => $error,
        'info' => $info
    )
);

?>
