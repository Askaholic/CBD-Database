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
Router::route_post( 'Members', 'members', 'members.php' );
Router::route_post( 'All users', 'users', 'users.php' );
Router::route_post( 'Create Event', 'create_event', 'create_event.php' );
Router::route_post( 'Sign up for Event', 'events', 'show_event.php' );
Router::route_post( 'Review Events', 'review_events', 'review_events.php' );
Router::route_post( 'View Invoice', 'invoices', 'show_invoice.php' );
Router::route_post( 'Login to your Contra Borealis Dance account', 'login', 'login.php' );
Router::route_post( 'Forgot Your Password?', 'forgot_password', 'forgot_password.php' );
Router::route_post( 'Reset Password', 'reset_password', 'reset_password.php' );
Router::route_post( 'Logout', 'logout', 'logout.php' );
Router::route_post( 'Profile', 'profile', 'profile.php' );
Router::route_post( 'User', 'user', 'user.php' );
?>
