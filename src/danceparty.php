<?php

/**
 * @package DanceParty
 */
/*
Plugin Name: Dance Party
Description: This plugin adds access for Contra Borealis member and event management
Author: DanceParty
Version: 0.0.0
 */

if ( !function_exists( 'add_action' ) ) {
  echo "You can't access plugins directly. To use this plugin, simply activate it through the admin menu.";
  exit;
}


define('DP_PLUGIN_DIR', plugin_dir_path(__FILE__));


register_activation_hook( __FILE__, array('DanceParty', 'activation_hook') );
register_deactivation_hook( __FILE__, array('DanceParty', 'deactivation_hook') );


require_once(DP_PLUGIN_DIR . 'class.danceparty.php');


add_action('admin_menu', 'danceparty_setup_menu');

function danceparty_setup_menu() {
  add_menu_page('Dance Party Settings', 'Dance Party', 'manage_options', 'danceparty-plugin', 'test_init');
}

function test_init() {
  include(DP_PLUGIN_DIR . 'views/settings.php');
}

add_filter( 'query_vars', 'dp_router_query_vars' );
function dp_router_query_vars( $query_vars )
{
    $query_vars[] = 'dp_router_page';
    return $query_vars;
}

add_action( 'parse_request', 'dp_router_parse_request' );
function dp_router_parse_request( &$wp ) {
    if ( array_key_exists( 'dp_router_page', $wp->query_vars ) ) {
        include 'controllers/test.php';
        exit();
    }
    return;
}

?>
