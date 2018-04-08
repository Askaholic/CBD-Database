<?php
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once(DP_PLUGIN_DIR . 'helpers.php');
require_once( DP_PLUGIN_DIR . 'models/event.php' );


$event_id = $_GET['event'];
$unscheduled = $_GET['unscheduled'];

if ( ! Authenticate::is_logged_in() ) {
    wp_redirect('login/?afterlog=' . "events/?event=$event_id");
}

$user = $_SESSION['usr'];
$user_id = $user->id;

if ( isset( $_POST['event_nonce'] ) && !wp_verify_nonce( $_POST['event_nonce'], 'submit' ) ) {
    die( 'Bad token' );
}

//admin only can view unenabled events
if($unscheduled)    //flag for admins to view by unscheduled index
{
    if( Authenticate::is_admin() ) {
        $event = Event::query_events_from_id( $event_id );
    }
    else {
        die ('Unauthorized');
    }
}
else
{
    if( not_empty( $event_id ) ) {
        //returns array of event objects
        $s_event = ScheduledEvent::query_events_from_id( $event_id );
    }
    $idx = $s_event->event_id;
    $event = Event::query_events_from_id( $idx );

}

$error = '';
$info = '';

//doesn't commit form if unscheduled event view by admin
if( isset( $_POST['event_nonce']) && !$unscheduled) {
//get default form info
//TODO is name necessary? This should be used to verify user address on file
    $first = valid_name( not_empty( $_POST['first_name'] ) );
    $last = valid_name( not_empty( $_POST['last_name'] ) );
    $output .= "first_name: $first\n";
    $output .= "last_name: $last\n";

//get custom form info
    $cols = (array) json_decode($event->schema_info);
    foreach($cols as $fields) {
        foreach ($fields as $field) {
            $name =  $field->name;
            $output .= " $name: " . not_empty($_POST[$name]) . "<br>";
            $val = not_empty($_POST[$name]);
            if($val)
                $arr[$name] = $val;
        }
    }
    $json = json_encode($arr);
    //echo "$json\n";
    //TODO verify matches user? makes more sense with address update
    // check if user has already signed up
    if( count( UserInvoices::query_invoices( $user_id, $event_id ) ) > 0 ) {
        $error = 'You have already signed up for this event';
    }
    else {
        $invoice = array(
            'user_id' => $user_id,
            'scheduled_event_id' => $event_id,
            'guest_amount' => 0,
            'invoice_amount' => 0.00,
            'amount_paid' => 0.00,
            'extra_info' => $json
        );

        //enter in database
        $ui = new UserInvoices($invoice);
        $ui->commit();

        $info = 'Event submission sent';
    }
    // echo "<br>DEBUG OUTPUT - FORM RESULTS: <br>";
    // echo $output;
}

DanceParty::render_view_for_event( $event, array(
        'error' => $error,
        'info' => $info,
        'user' => $user
    )
 );

?>
