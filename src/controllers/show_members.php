<?php

/* show_members.php
 * Rohan Weeden
 * Created: March 12 , 2018
 */

require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );


if ( ! Authenticate::logged_in() ) {
    // TODO: Redirect to login page
}

if ( ! Authenticate::is_door_host() ) {
    die( 'Unauthorized' );
}

$title = 'Active Member List';
$error;
$info;
$members = array();

try {
    $members = User::query_all_with_membership();
}
catch (Exception $e) {
    error_log($e);
    /*
     * Display an error message if something goes wrong
     */
    $error = $e->getMessage();

    if ( get_class($e) === PDOException) {
        $error = "Database error";
    }
}

DanceParty::render_view_with_template( 'show_members.php',
    array(
        'title' => $title,
        'members' => $members,
        'error' => $error,
        'info' => $info
    )
);

?>
