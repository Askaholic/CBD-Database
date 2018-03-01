<?php

require_once( DP_PLUGIN_DIR . 'models/user.php' );
require_once( DP_PLUGIN_DIR . 'models/event.php' );
require_once( DP_PLUGIN_DIR . 'class.passwordhash.php' );

// $events = Event::query_all();
// $event = $events[1];
//
// $event->create();
//
// $user = new User('Rohan', 'Weeden', 'asdf@asdf.asdf');
// $user->sadf;
$hash = Password::hash('asdf');
echo 'Hash is: ' . $hash . '<br/>';
echo Password::verify('asdf', $hash) ? 'Passwords match! ' : 'Passwords dont match' . '<br/>';
echo Password::verify('fdsa', $hash) ? 'Bad pass matches, uh oh' : 'Bad password correctly does not match'; 
?>

<h1>User model test</h1>
