<?php

require_once(DP_PLUGIN_DIR . 'class.danceparty.php');

/**
 * DancePartyAdmin
 * Initializes the admin page for the plugin
 */
class DancePartyAdmin
{
    private static $init_done = false;

    static function init() {
        if ( !self::$init_done ) {
            self::init_hooks();
        }
    }

    static function init_hooks() {
        add_action( 'admin_menu', array( 'DancePartyAdmin', 'setup_menu') );

        self::$init_done = true;
    }

    static function setup_menu() {
        add_menu_page( DanceParty::NAME . ' Settings',
                       DanceParty::NAME, 'manage_options',
                       'danceparty-plugin',
                       array('DancePartyAdmin', 'admin_view') );
    }

    static function admin_view() {
        DanceParty::render_view( 'plugin_admin.php' );
    }
}

?>
