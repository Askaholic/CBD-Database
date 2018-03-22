<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


require_once( DP_PLUGIN_DIR . 'models/roles.php' );
require_once( DP_PLUGIN_DIR . 'models/user.php' );

class Authenticate
{
	private static function start() {
		if (session_status() == PHP_SESSION_NONE)
    		session_start();
    }

	public static function is_logged_in() {
		Authenticate::start();
		if (isset($_SESSION['user']))
			return true;
		return false;
	}

	public static function is_admin() {
		Authenticate::start();
		if (isset($_SESSION['user'])) {
			if (isset($_SESSION['role']) && $_SESSION['role'] == '3')
				return true;
		}
		return false;
	}


	public static function is_door_host() {
		Authenticate::start();
		if (isset($_SESSION['user'])) {
			if (isset($_SESSION['role']) && ($_SESSION['role'] == '2' || $_SESSION['role'] == '3'))
				return true;
		}
		return false;
	}
}
