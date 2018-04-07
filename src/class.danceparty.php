<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

require_once( DP_PLUGIN_DIR . 'class.router.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );
require_once( DP_PLUGIN_DIR . 'class.authenticate.php');

/*
 * Need to define constants because old versions of php (which iPage appraently
 * still uses) do not support concatenating constant values and strings...
 */

define( 'DP_CONTROLLER_DIR', DP_PLUGIN_DIR . 'controllers/' );
define( 'DP_VIEW_DIR', DP_PLUGIN_DIR . 'views/' );
define( 'DP_ASSET_URL', DP_PLUGIN_URL . 'assets/' );

/**
 * DanceParty
 */
class DanceParty
{
    const NAME = 'Dance Party';
    const OPTION_GROUP = 'danceparty-options';
    const CONTROLLER_DIR = DP_CONTROLLER_DIR;
    const VIEW_DIR = DP_VIEW_DIR;

    const ASSET_URL = DP_ASSET_URL;

    private static $init_done = false;

    public static function add_login_logout_menu($items, $args) {
        if( Authenticate::is_logged_in() )
            $link .= '<a href="' . '/logout' . '" title="Logout">' . __( 'Logout' ) . '</a>';
        else
            $link = '<a href="' . '/login' . '" title="Login">' . __( 'Login' ) . '</a>';

        return $items .= '<li id="login_logout_menu-link" class="menu-item menu-type-link">'. $link . '</li>';
    }

    public static function add_profile_menu($items, $args) {
        if( Authenticate::is_logged_in() )
            $link .= '<a href="' . '/profile' . '" title="Profile">' . __( 'Profile' ) . '</a>';

        return $items .= '<li id="profile_menu-link" class="menu-item menu-type-link">'. $link . '</li>';
    }

    static function init() {
        if ( !self::$init_done) {
            self::init_hooks();
        }
    }

    static function init_hooks() {
        Router::init_hooks();

        add_action( 'wp_enqueue_scripts', array( 'DanceParty', 'enqueue_scripts_and_styles' ) );

        add_filter( 'wp_nav_menu_items', array( 'DanceParty','add_profile_menu'), 20, 5);

        add_filter( 'wp_nav_menu_items', array( 'DanceParty','add_login_logout_menu'), 20, 5);

        $init_done = true;
        ob_start();
    }

    /* Called when the plugin is activated through the admin panel */
    public static function activation_hook() {
      Router::register_routes();
      flush_rewrite_rules();

      // self::create_tables();
    }

    public static function deactivation_hook() {
        flush_rewrite_rules();
    }

    public static function create_tables() {
        require_once( DP_PLUGIN_DIR . 'models/user.php' );
        require_once( DP_PLUGIN_DIR . 'models/event.php' );
        require_once( DP_PLUGIN_DIR . 'models/roles.php' );
        require_once( DP_PLUGIN_DIR . 'models/tokens.php' );

        User::create_table();
        UserInvoices::create_table();
        Membership::create_table();
        Event::create_table();
        ScheduledEvent::create_table();
        Role::create_table();
        Token::create_table();
    }

    public static function enqueue_scripts_and_styles() {
        wp_enqueue_style( 'fallback-css', self::ASSET_URL . 'fallback.css' );
        wp_enqueue_script( 'angularjs-1.6.7', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.6.7/angular.min.js' );
        wp_enqueue_script( 'app-event-creator', self::ASSET_URL . 'event_creator.js' );
        wp_enqueue_script( 'danceparty', self::ASSET_URL . 'danceparty.js' );
    }

    public static function render_view( $view, $context = array() ) {
        include_once( 'class.formbuilder.php' );
        // Escape dangerous characters
        array_map("recurse_htmlspecialchars", $context);
        extract($context);

        include DanceParty::VIEW_DIR . $view;
    }

    public static function render_view_with_template( $view, $context = array() ) {
        extract($context);

        include DanceParty::VIEW_DIR . 'layout.php';
    }

    public static function render_view_for_event( $event, $context = array() ) {

        if( empty($event) ) {
             self::render_view_with_template('event_not_found.php');
        }
        else {
            $context['event'] = $event;
            self::render_view_with_template( 'show_event.php' , $context );
        }

    }

    public static function run_controller( $controller ) {
        include DanceParty::CONTROLLER_DIR . $controller;
    }
}

?>
