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
    wp_redirect(get_page_link(get_page_by_path('login')) . '?afterlog=review_events');
}

if ( ! Authenticate::is_admin() ) {
    die( 'Unauthorized' );
}


$nonce_name = 'event_review_nonce';
if ( isset( $_POST[$nonce_name] ) && !wp_verify_nonce( $_POST[$nonce_name], 'submit' ) ) {
    die( 'Bad token' );
}

$error;
$info;
$events;

try {
    $events = Event::query_all();

    if ( isset( $_POST[$nonce_name] ) ) {
        $event_id = valid_id(not_empty($_POST['event_id']));
        $action_type = not_empty($_POST['action_type']);

        $event = new Event(
            array(
                'id' => $event_id
            )
        );
        $event->pull();

        if ($action_type === 'preview') {
            DanceParty::render_view( 'event_snippet.php',
                array(
                    'event' => $event
                )
            );
        }
        else if($action_type === 'schedule') {
            $start_date = valid_date(not_empty($_POST['start_date']));
            $end_date = valid_date(not_empty($_POST['end_date']));

            $sched_event = new ScheduledEvent(
                array(
                    'event_id' => $event_id,
                    'start_date' => $start_date,
                    'end_date' => $end_date
                )
            );
            $sched_event->commit();

            $info = "Successfully scheduled ". htmlspecialchars($event->name);
        }
        else {
            throw new BadInputException("Unknown action");
        }
    }

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
