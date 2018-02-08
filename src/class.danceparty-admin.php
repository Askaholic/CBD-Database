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
        add_action( 'admin_menu', array( 'DancePartyAdmin', 'setup_menu' ) );
        add_action( 'admin_init', array( 'DancePartyAdmin', 'register_settings' ) );

        self::$init_done = true;
    }

    static function setup_menu() {
        $page_title = DanceParty::NAME . ' Options';
        $menu_title = DanceParty::NAME;
        $capability = 'manage_options';
        $menu_slug = 'danceparty-plugin';
        $function = array( 'DancePartyAdmin', 'admin_view' );
        add_menu_page( $page_title, $menu_title, $capability,
                       $menu_slug, $function);
    }

    static function register_settings() {
        $option_group = DanceParty::OPTION_GROUP;
        $option_name = DanceParty::OPTION_GROUP . '-options';
        register_setting( $option_group, $option_name );

        $db_section = DanceParty::OPTION_GROUP . '-db';
        add_settings_section(
            $db_section,
            'Database Options',
            array( 'DancePartyAdmin', 'db_section' ),
            $option_group
        );

        add_settings_field(
            'db_host',
            'Host',
            array( 'DancePartyAdmin', 'db_host_field_html' ),
            $option_group,
            $db_section
        );
    }

    static function db_section() {}
    static function db_host_field_html() {
        echo '<input id="db_host" name="db_host" type="text"/>';
    }

    static function admin_view() {
        DanceParty::render_view( 'plugin_admin.php' );
    }
}

?>
