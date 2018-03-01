<?php

require_once( DP_PLUGIN_DIR . 'models/user.php' );
require_once( DP_PLUGIN_DIR . 'models/event.php' );
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );

// $events = Event::query_all();
// $event = $events[1];
//
// $event->create();
//
// $user = new User('Rohan', 'Weeden', 'asdf@asdf.asdf');
// $user->sadf;

$GLOBALS['session']->set('id', 1);

echo (Authenticate::logged_in() ? 'true': 'false');

?>

<h1>User model test</h1>
