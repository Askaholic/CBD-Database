<?php

/*
 * Include all routes here.
 * Format:
 *     Router::route( $endpoint, $controller );
 */

Router::route_post( 'Test' , 'test', 'test.php' );
Router::route( 'umt', 'umt.php' );

Router::route_post( 'Sign Up', 'signup', 'signup.php' );
Router::route_post( 'Create Member', 'create_member', 'create_member.php' );
Router::route_post( 'Show Members', 'show_members', 'show_members.php' );
Router::route_post( 'Show Expired Members', 'show_expired_members', 'show_expired_users.php' );
Router::route_post( 'Create Event', 'create_event', 'create_event.php' );
Router::route_post( 'Login', 'login', 'login.php' );
Router::route_post( 'Forgot Password', 'forgot_password', 'forgot_password.php' );
Router::route_post( 'Reset Password', 'reset_password', 'reset_password.php' );

?>
