<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


require_once( DP_PLUGIN_DIR . 'models/roles.php' );

class Authenticate
{
	public static function is_logged_in() {
		$id = $GLOBALS['session']->get( 'id' );
		if( empty( $id ) )
			return false;
		return true;
	}

	public static function is_admin() {
		$role_id = $GLOBALS['session']->get( 'role_id' );
		if( empty( $role_id ) || $role_id !== Role::ROLE_IDS['ADMIN'])
			return false;
		return true;
	}


	public static function is_door_host() {
		$role_id = $GLOBALS['session']->get( 'role_id' );
		if( empty( $role_id ) || $role_id !== Role::ROLE_IDS['DOOR_HOST'])
			return false;
		return true;
	}
}
