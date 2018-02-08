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

register_activation_hook( __FILE__, array( 'DanceParty', 'activation_hook' ) );
register_deactivation_hook( __FILE__, array( 'DanceParty', 'deactivation_hook' ) );

require_once( DP_PLUGIN_DIR . 'class.danceparty.php' );

add_action( 'init', array( 'DanceParty', 'init' ) );

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( DP_PLUGIN_DIR . 'class.danceparty-admin.php' );
	add_action( 'init', array( 'DancePartyAdmin', 'init' ) );
}

//adding template hook actions
//just example actions for now
function writeToFooter()
{
  echo "<p>Hi footer</p>";
}
function writeToHeader()
{
//  echo "<p>Hi header</p>";
}
function writeToMenu()
{
  echo '<a href="signup">Sign Up</a>';
}
function writeToIframeMenu()
{
  //I'm pretty sure there's a better way of doing this
  echo '<form style="float: left" method="post" action="test">';
  echo '<input type="hidden" name="menu" value="index.php/home">';
  echo '<input type="submit" value="Home"></form>';

  echo '<form style="float: left" method="post" action="test">';
  echo '<input type="hidden" name="menu" value="signup">';
  echo '<input type="submit" value="Sign Up"></form>';
}


add_action('wp_footer', 'writeToFooter');
add_action('wp_head', 'writeToHeader');
add_action('wp_meta', 'writeToMenu');
add_action('wp_iframe', 'writeToIframeMenu');
?>
