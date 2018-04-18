<?php
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once(DP_PLUGIN_DIR . 'helpers.php');
require_once( DP_PLUGIN_DIR . 'models/event.php' );


$event_id = $_GET['event'];
$unscheduled = $_GET['unscheduled'];

if ( ! Authenticate::is_logged_in() ) {
    wp_redirect(get_page_link(get_page_by_title('login')) . '?afterlog=events/?event=$event_id');
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


    //parse form info TODO write to new table specific to event
    $cols = (array) json_decode($event->schema_info);
    foreach($cols as $fields) {
        foreach ($fields as $field) {
            $name =  $field->name;
            //$output .= " $name: " . not_empty($_POST[$name]) . "<br>";
            $val = not_empty($_POST[$name]);
            if($val)
                $arr[$name] = $val;
        }
    }
    $json = json_encode($arr);

    //calculate total due and guests
    $total_due = not_empty( $_POST['total_due']);
    $children = not_empty( $_POST['children']);
    $young_adults = not_empty( $_POST['young_adults']);
    $adults = not_empty( $_POST['adults']);
    $guests = $children+$young_adults+$adults;

    // check if user has already signed up
    if( count( UserInvoices::query_invoices( $user_id, $event_id ) ) > 0 ) {
        $error = 'You have already signed up for this event';
    }
    else {
        $invoice = array(
            'user_id' => $user_id,
            'scheduled_event_id' => $event_id,
            'guest_amount' => $guests,
            'invoice_amount' => $total_due,
            'amount_paid' => 0.00,
        );

        //enter in database
        $ui = new UserInvoices($invoice);
        $ui->commit();

        $info = 'Event submission sent';
    }

}

DanceParty::render_view_for_event( $event, array(
        'error' => $error,
        'info' => $info,
        'user' => $user
    )
 );

?>
