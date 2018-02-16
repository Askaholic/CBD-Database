<?php

if ( isset($_POST['event_schema']) ) {
    echo $_POST['event_schema'];
    $schema = json_decode($_POST['event_schema']);
    echo "Schema: " . $schema['name'];
}
else {
    DanceParty::render_view( 'create_event.php' );

}

?>
