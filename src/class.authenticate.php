<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


require_once( DP_PLUGIN_DIR . 'models/roles.php' );

class Authenticate
{
	public static function is_logged_in() {
		$id = $_SESSION['user'];
		//$id = $GLOBALS['session']->get( 'id' );
		if( empty( $id ) ) {
			return false;
		}
		return true;
	}

	public static function is_admin() {
		$id = $_SESSION['user'];
		if( empty( $id ) || $id !== '3')
			return false;
		return true;
	}


	public static function is_door_host() {
		$id = $_SESSION['user'];
		if( empty( $id ) || $id !== '2' || $id !== '3')
			return false;
		return true;
	}
}
