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


define( 'DP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DP_PLUGIN_URL', plugins_url('/', __FILE__ ) );

register_activation_hook( __FILE__, array( 'DanceParty', 'activation_hook' ) );
register_deactivation_hook( __FILE__, array( 'DanceParty', 'deactivation_hook' ) );

require_once( DP_PLUGIN_DIR . 'class.danceparty.php' );

add_action( 'init', array( 'DanceParty', 'init' ) );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( DP_PLUGIN_DIR . 'class.danceparty-admin.php' );
	add_action( 'init', array( 'DancePartyAdmin', 'init' ) );
}

//set theme
require_once( DP_PLUGIN_DIR . 'class.theme.php' );

//set session
require_once( DP_PLUGIN_DIR . 'class.danceparty_session.php' );
function dp_session() {

	global $session;
	$session = new DanceParty_Session();

}
add_action( 'init', 'dp_session' );

?>
