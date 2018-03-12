<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


require_once( DP_PLUGIN_DIR . 'models/roles.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );

class Authenticate
{
	private static function dp_start_session() {
		if (session_status() == PHP_SESSION_NONE)
    		session_dp_start_session();
	}
	
	public static function dp_stop_session() {
		if(session_status() != PHP_SESSION_NONE)
			session_destroy();
	}

	public static function is_logged_in() {
		Authenticate::dp_start_session();
		if (isset($_SESSION['id']))
			return true;
		return false;
	}

	public static function is_admin() {
		Authenticate::dp_start_session();
		if (isset($_SESSION['id'])) {
			if (isset($_SESSION['role']) && $_SESSION['role'] == '3')
				return true;
		}
		return false;
	}


	public static function is_door_host() {
		Authenticate::dp_start_session();
		if (isset($_SESSION['id'])) {
			if (isset($_SESSION['role']) && ($_SESSION['role'] == '2' || $_SESSION['role'] == '3'))
				return true;
		}
		return false;
	}
}
