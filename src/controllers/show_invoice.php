<?php
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once(DP_PLUGIN_DIR . 'helpers.php');
require_once( DP_PLUGIN_DIR . 'models/event.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );


$inv_id = $_GET['invoice'];
$user_id = $_GET['user'];
if ( ! Authenticate::is_logged_in() ) {
    if( not_empty($user_id) )
        wp_redirect(get_page_link(get_page_by_path('login')) . "?afterlog=invoices/?invoice=$inv_id&user=$user_id");
    else
        wp_redirect(get_page_link(get_page_by_path('login')) . "?afterlog=invoices/?invoice=$inv_id");
}

if( isset($user_id) ) {
    //getting other user's invoice
    if( Authenticate::is_admin() ) {
            $user = User::query_users_from_id( $user_id )[0];
    }
    else die ('Unauthorized');
}
else {
    //getting own invoice
    $user = $_SESSION['usr'];
}

//get user invoice info
$user_inv = UserInvoices::query_invoice( $inv_id );

//get event invoice info
//make table name
$s_event = ScheduledEvent::query_events_from_id( $user_inv->scheduled_event_id );
$event = Event::query_events_from_id( $s_event->event_id );
$date = str_replace('-', '', $s_event->start_date);
$name = str_replace(' ', '_', $event->name);
$table_name = $date . '_' . clean_name($name);

if($table_name !== '_') {

    //query database
    $db = new PDOConnection();
    $sql = "SELECT * FROM $table_name WHERE invoice = $inv_id";

    $event_inv = $db->raw_query( $sql );
}

DanceParty::render_view_for_invoice( $user_inv, $event_inv, array(
        'error' => $error,
        'info' => $info,
        'event' => $event
    )
 );

?>
