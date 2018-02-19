<?php

require_once(DP_PLUGIN_DIR . 'models/user.php');

if(!isset($_POST['create_member_nonce']) || !wp_verify_nonce($_POST['create_member_nonce'], 'submit'))
{
  //do nothing - either form hasn't been submitted or bad nonce
}
else //check form
{
  $name = $_POST['user'];
  $email = $_POST['email'];
  $expiry = $_POST['expiry'];
  $pass = ''; // empty pass for admin-created members
  if(!empty($name) && !empty($email))
  {
      // commit user data
      $splitName = explode(" ", $name);
      $userData = array('first_name' => $splitName[0],'last_name' => $splitName[1],
                        'email' => $email, 'password' => $pass,'role_id' => 0);
      $user = new User($userData);
      $new_id = $user->commit_id();

      // commit membership data
      $memberData = array('user_id' => $new_id, 'expiration_date' => $expiry);
      $member = new Membership($memberData);
      $member->commit();

      $out = "$email account created, with expiry date $expiry";
  }
  echo "<p>$out</p>";
}

DanceParty::render_view_with_template( 'create_member.php', array('members' => $members) );

?>