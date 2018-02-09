<?php

require_once(DP_PLUGIN_DIR . 'class.danceparty.php');

/* ------------------------------------------------------------------------ *
 * DancePartyAdmin Class
 * ------------------------------------------------------------------------ */
/**
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
        add_action( 'admin_init', array( 'DancePartyAdmin', 'database_settings' ) );

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

    static function admin_view() {
        DanceParty::render_view( 'plugin_admin.php' );
    }

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */
/**
 * Initializes the database options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */
    static function database_settings() {
        $option_group = DanceParty::OPTION_GROUP;
	$db_section = DanceParty::OPTION_GROUP . '-db';

        add_settings_section(
            $db_section,
            'Database Options',
            array( 'DancePartyAdmin', 'db_section' ),
            $option_group
        );

	// USERNAME
	add_settings_field(
            'db_user',
            'Username',
            array( 'DancePartyAdmin', 'db_user_field_html' ),
            $option_group,
            $db_section
        );
	register_setting( $option_group, 'db_user', 'database_settings_validate');

	//PASSWORD

	add_settings_field(
            'db_password',
            'Password',
            array( 'DancePartyAdmin', 'db_password_field_html' ),
            $option_group,
            $db_section
        );
	register_setting( $option_group, 'db_password', 'database_settings_validate');

	//HOSTNAME

	add_settings_field(
            'db_name',
            'Hostname',
            array( 'DancePartyAdmin', 'db_name_field_html' ),
            $option_group,
            $db_section
        );
	register_setting( $option_group, 'db_name', 'database_settings_validate');

	//HOST

        add_settings_field(
            'db_host',
            'Host',
            array( 'DancePartyAdmin', 'db_host_field_html' ),
            $option_group,
            $db_section
        );
	register_setting( $option_group, 'db_host', 'database_settings_validate');

	//PORT

        add_settings_field(
            'db_port',
            'Port',
            array( 'DancePartyAdmin', 'db_port_field_html' ),
            $option_group,
            $db_section
        );
	register_setting( $option_group, 'db_port', 'database_settings_validate');
    }

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */
/**
 * This function provides a simple description for the Database Options page.
 *
 * It is called from the 'database_settings' function by being passed as a parameter
 * in the add_settings_section function.
 */

    static function db_section() {
	echo '<p>Enter the login and information for the database you wish to connect to.</p>';
    }

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */
/**
 * These functions render the interface elements for entering database information.
 */

    static function db_user_field_html() {
        echo '<input id="db_user" name="db_user" value="' . get_option('db_user') . '" type="text" />';
    }
    static function db_password_field_html() {
        echo '<input id="db_password" name="db_password" type="password"/>';
    }
    static function db_name_field_html() {
        echo '<input id="db_name" name="db_name" value="' . get_option('db_name') . '" type="text" />';
    }
    static function db_host_field_html() {
        echo '<input id="db_host" name="db_host" value="' . get_option('db_host') . '" type="text" />';
    }
    static function db_port_field_html() {
        echo '<input id="db_port" name="db_port" value="' . get_option('db_port') . '" type="text" />';
    }

/* ------------------------------------------------------------------------ *
 * Input Validation
 * ------------------------------------------------------------------------ */
/**
 * This function examine the user input and determine whether or not it's acceptable to save.
 *
 * It is called from the 'database_settings' function by being passed as a parameter
 * in the register_setting function.
 */

    function database_settings_validate( $input ) {
	$output = array();
   	foreach( $input as $key => $value ) {
        if( isset( $input[$key] ) ) {
            $output[$key] = strip_tags( stripslashes( $input[$key] ));
        }
    }
    return apply_filters( 'database_settings_validate', $output, $input );
    }

}

?>
