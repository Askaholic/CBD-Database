<?php

require_once( DP_PLUGIN_DIR . 'class.authenticate.php');
if (Authenticate::is_logged_in() )
    Authenticate::dp_stop_session();

wp_redirect('/');

?>