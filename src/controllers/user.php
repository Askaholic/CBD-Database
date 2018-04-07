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
    wp_redirect('login/?afterlog=user');
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

$nonce_name = 'user_nonce';
if ( isset( $_POST[$nonce_name] ) && !wp_verify_nonce( $_POST[$nonce_name], 'submit' ) ) {
    die( 'Bad token' );
}

$user_id = $_GET['user_id'];
$error = '';
$info = '';

if ( isset( $_POST[$nonce_name] ) ) {
    try {
        /*
         * Get and validate arguments
         */
        $first =  not_empty( $_POST['first'] );
        $last = not_empty( $_POST['last'] );

        $user = User::query_users_from_id( $user_id );

        /*
         * Do some logic
         */
        if ( count( $user ) == 0 ) {
            throw new BadInputException( "Incorrect or unregistered id." );
        }

        $user[0]->update('first_name', $first, 'id', $user[0]->id);
            
        $user[0]->update('last_name', $last, 'id', $user[0]->id);

        /*
         * Display a confirmation message
         */

        $info = "Successfully changed account information";
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

DanceParty::render_view_with_template( 'user.php',
    array(
        'error' => $error,
        'info' => $info,
        'user_id' => $user_id
    )
);

?>
