<?php
/* review_events.php
 * Rohan Weeden
 * Created: March 20 , 2018
 *
 * Page for allowing an admin to accept or decline an event.
 */

/*
 * Require dependencies here
 */

require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );
require_once( DP_PLUGIN_DIR . 'models/event.php' );

if ( ! Authenticate::is_logged_in() ) {
    wp_redirect('login');
}

if ( ! Authenticate::is_admin() ) {
    // die( 'Unauthorized' );
}


$nonce_name = 'event_review_nonce';
if ( isset( $_POST[$nonce_name] ) && !wp_verify_nonce( $_POST[$nonce_name], 'submit' ) ) {
    die( 'Bad token' );
}

$error;
$info;
$events;

try {
    if ( isset( $_POST[$nonce_name] ) ) {
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

    $events = Event::query_all();
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

DanceParty::render_view_with_template( 'review_events.php',
    array(
        'error' => $error,
        'info' => $info,
        'events' => $events
    )
);

?>
