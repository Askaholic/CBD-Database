<?php
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once(DP_PLUGIN_DIR . 'helpers.php');
require_once( DP_PLUGIN_DIR . 'models/event.php' );


$event_id = $_GET['event'];
$unscheduled = $_GET['unscheduled'];

if ( ! Authenticate::is_logged_in() ) {
    wp_redirect(get_page_link(get_page_by_title('login')) . "?afterlog=events/?event=$event_id");
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

//TODO make this not display event form also
//TODO should this be registration_end_date?
if( date($s_event->end_date) < date('Y-m-d') ) {
    $error = 'Event Registration has ended';
    $expired = true;

}

//doesn't commit form if unscheduled event view by admin
if( isset( $_POST['event_nonce']) && !$unscheduled && !$expired) {

    $schema_types = [];
    $schema_values = [];
    $special_types = ['userinfo','eventdesc','childinfo','payinfo','costinfo'];

    //parse form info TODO write to new table specific to event
    $cols = (array) json_decode($event->schema_info);
    foreach($cols as $fields) {
        foreach ($fields as $field) {
            $type = $field->type;
            if(in_array($type, $special_types)) {
                if($type === 'userinfo') {
                    $first_name = not_empty($_POST['first_name']);
                    $last_name = not_empty($_POST['last_name']);
                    $schema_types['first_name'] = 'text';
                    $schema_types['last_name'] = 'text';
                    if( not_empty($first_name) )
                        $schema_values['first_name'] = $first_name;
                    if( not_empty($last_name) )
                        $schema_values['last_name'] = $last_name;
                }
                else if($type === 'childinfo') {
                    $children = not_empty($_POST['children']);
                    $young_adults = not_empty($_POST['young_adults']);
                    $adults = not_empty($_POST['adults']);
                    $schema_types['children'] = 'int DEFAULT 0';
                    $schema_types['young_adults'] = 'int DEFAULT 0';
                    $schema_types['adults'] = 'int DEFAULT 0';
                    if( not_empty($children) )
                        $schema_values['children'] = $children;
                    if( not_empty($young_adults) )
                        $schema_values['young_adults'] = $young_adults;
                    if( not_empty($adults) )
                        $schema_values['adults'] = $adults;
                }
                // else if($type === 'eventdesc') {
                //     $schema_types['description'] = 'text';
                //     $description = $field->description;
                //     if( not_empty($description) )
                //         $schema_values['description'] = $description;
                // }
                else {
                    //payinfo is just a checkbox
                    //TODO if more than one payment method is added update here
                    continue;
                }

            }
            else if( $type === 'multivalued' || $type === 'checkbox' ) {
                foreach( $field->options as $option) {
                    $schema_types[$option] = 'BOOLEAN';
                    $val = not_empty($_POST[$option]);
                    if( not_empty($val) )
                        $schema_values[$option] = 1;
                    else $schema_values[$option] = 0;
                }
            }
            else {
                $name =  $field->name;

                $val = not_empty($_POST[$name]);
                $schema_types[$name] = $field->type;
                if( not_empty($val) )
                    $schema_values[$name] = $val;
            }
        }
    }
    //TODO remove when sure we don't need to store here
    //manual entries
    // $child_cost = not_empty($_POST['child_cost']);
    // $schema_types['child_cost'] = 'double DEFAULT 0';
    // if( not_empty($child_cost) )
    //     $schema_values['child_cost'] = $child_cost;
    // $adult_cost = not_empty($_POST['adult_cost']);
    // $schema_types['adult_cost'] = 'double DEFAULT 0';
    // if( not_empty($adult_cost) )
    //     $schema_values['adult_cost'] = $adult_cost;
    // $young_adult_cost = not_empty($_POST['young_adult_cost']);
    // $schema_types['young_adult_cost'] = 'double DEFAULT 0';
    // if( not_empty($young_adult_cost) )
    //     $schema_values['young_adult_cost'] = $young_adult_cost;

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
    else
    {

        $invoice = array(
            'user_id' => $user_id,
            'scheduled_event_id' => $event_id,
            'guest_amount' => $guests,
            'invoice_amount' => $total_due,
            'amount_paid' => 0.00,
        );

        //enter invoice in database
        $ui = new UserInvoices($invoice);
        $ui->commit();

        $inv = UserInvoices::query_invoices( $user_id, $event_id )[0];
        if( not_empty($inv) ) {

            //this is primary key so guaranteed unique
            $id = intval( $inv->id );
            $schema_types = array( 'invoice' => 'int NOT NULL PRIMARY KEY' ) + $schema_types;
            $schema_values['invoice'] = $id;
            //enter in event table
            $table = new Event( array() );
            $table->create($schema_values, $schema_types, str_replace('-', '',
                           $s_event->start_date), str_replace(' ', '_', $event->name));

            $info = 'Event submission sent';
        }

    }

}

DanceParty::render_view_for_event( $event, array(
        'error' => $error,
        'info' => $info,
        'user' => $user
    )
 );

?>
