<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Authenticate{
	
	public function logged_in() {
		$id = $GLOBALS['session']->get( 'id' );
		if(empty(id))
			return false;
		return true;
	}
	
	public function is_admin() {
		$role_id = $GLOBALS['session']->get( 'role_id' );
		if(empty(role_id))
			return false;
		return true;
	}
}
