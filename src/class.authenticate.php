<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//DanceParty()->session->set( 'item_name', $value );

class Authenticate{
	
	public function logged_in() {
		$id = DanceParty()->session->get( 'id' );
		if(!id)
			return false;
		return true;
	}
	
	public function is_admin() {
		$role_id = DanceParty()->session->get( 'role_id' );
		if(!role_id)
			return false;
		return true;
	}
}