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

add_action('admin_menu', 'danceparty_setup_menu');

function danceparty_setup_menu() {
  add_menu_page('Dance Party Settings', 'Dance Party', 'manage_options', 'danceparty-plugin', 'test_init');
}

function test_init() {
  include(plugin_dir_path( __FILE__ ) . 'views/settings.php');
}

?>
