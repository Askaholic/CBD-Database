<?php

require_once( DP_PLUGIN_DIR . 'models/user.php' );

$members = User::query_all_without_membership();

if(!isset($_POST['renew_member_nonce']) || !wp_verify_nonce($_POST['renew_member_nonce'], 'submit'))
{
  //do nothing - either form hasn't been submitted or bad nonce
}
else //check form
{
  $id = $_POST['id'];
  $expiry = $_POST['expiry'];
  if(!empty($id) && !empty($expiry))
  {
      // commit user data
      $memberData = array('user_id' => $id, 'expiration_date' => $expiry);
      $member = new Membership($memberData);
      $member->commit();

      $out = "$id renewed, with expiry date $expiry";
  }
  echo "<p>$out</p>";
}

DanceParty::render_view_with_template( 'show_expired_users.php', array('members' => $members) );

?>
