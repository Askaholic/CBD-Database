<?php

require_once( DP_PLUGIN_DIR . 'models/user.php' );

$members = User::query_all_without_membership();

DanceParty::render_view_with_template( 'show_expired_users.php', array('members' => $members) );

if(!isset($_POST['renew_member_nonce']) || !wp_verify_nonce($_POST['renew_member_nonce'], 'submit'))
{
  //do nothing - either form hasn't been submitted or bad nonce
}
else //check form
{
  echo "<p>Renewing user</p>";
}


?>
