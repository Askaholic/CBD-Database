<?php
/* controller_template.php
 * Rohan Weeden
 * Created: Janurary 27, 2019
 *
 * Snippet for showing either the login link if you are not logged in, or the
 * logout link if you are logged in.
 */


require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );


if( Authenticate::is_logged_in() )
    echo '<a href="' . get_page_link(get_page_by_path('logout')) . '" title="Logout">' . __( 'Logout' ) . '</a>';
else
    echo '<a href="' . get_page_link(get_page_by_path('login')) . '" title="Login">' . __( 'Login' ) . '</a>';

?>
