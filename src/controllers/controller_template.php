<?php

/* controller_template.php
 * Rohan Weeden
 * Created: March 7 , 2018
 *
 * General rules for a controller
 *  Controllers handle backend logic and interaction with the data.
 *  - This file should not contain ANY HTML! (That goes in the view)
 *
 * Modify these comments to reflect your controller purpose. It's pretty obvious
 * that you either forgot to do this, or can't read because this comment is still
 * here...
 */


/*
 * Require dependencies here
 */

require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );


/*
 * Keep this part if you need the user to be logged in
 */
if ( ! Authenticate::is_logged_in() ) {
    // TODO: Redirect to login page
}


/*
 * Keep this part if you need the user to be an admin
 */
if ( ! Authenticate::is_admin() ) {
    die( 'Unauthorized' );
}

/*
 * Keep this part if you need to handle form data
 */

$nonce_name = 'change_this';
if ( isset( $_POST[$nonce_name] ) && !wp_verify_nonce( $_POST[$nonce_name], 'submit' ) ) {
    die( 'Bad token' );
}

$error;
$info;

if ( isset( $_POST[$nonce_name] ) ) {
    try {
        /*
         * Get and validate arguments
         */
        $var = valid_name(not_empty($_POST['var']));

        /*
         * Do some logic
         */


        /*
         * Display a confirmation message
         */
        $info = "Success";
    }
    catch ( Exception $e ) {
        if ( get_class( $e ) !== BadInputException ) {
            error_log($e);
        }
        /*
         * Display an error message if something goes wrong
         */
        $error = $e->getMessage();

        if ( get_class( $e ) === PDOException ) {
            $error = "Database error";
        }
    }
}

DanceParty::render_view_with_template( 'view.php',
    array(
        'error' => $error,
        'info' => $info
    )
);

?>
