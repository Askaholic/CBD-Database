<?php

/*
 * Include all routes here.
 * Format:
 *     Router::route( $endpoint, $controller );
 */

Router::route( 'test', 'test.php' );
Router::route( 'signup', 'signup.php' );
Router::route( 'create_member', 'create_member.php' );
Router::route( 'user_model_test', 'umt.php' );
Router::route( 'show_members', 'show_members.php' );
Router::route( 'show_expired_members', 'show_expired_users.php' );
Router::route( 'Cameron_test', 'Cameron_test.php' );
Router::route( 'create_event', 'create_event.php' );
Router::route( 'login', 'login.php' );
?>
