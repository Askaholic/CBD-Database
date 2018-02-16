<?php

require_once( DP_PLUGIN_DIR . 'models/user.php' );
require_once( DP_PLUGIN_DIR . 'models/event.php' );

$events = Event::query_all();
$event = $events[1];

$event->create();
//
// $user = new User('Rohan', 'Weeden', 'asdf@asdf.asdf');
// $user->sadf;

?>

<h1>User model test</h1>
