<?php

require_once(DP_PLUGIN_DIR . 'models/user.php');

  if(!isset($_POST['create_member_nonce']) || !wp_verify_nonce($_POST['create_member_nonce'], 'submit'))
  {
    //do nothing - either form hasn't been submitted or bad nonce
  }
  else //check form
  {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $email = $_POST['email'];
    $expiry = $_POST['expiry'];
    $pass = ''; // empty pass for admin-created members
    if(!empty($first) && !empty($last) && !empty($email) && !empty($expiry))
    {
      $input_check = true;

      $test = preg_match('/^[A-Za-z]{2,255}$/', $first);
      if($test === false)
        $input_check = false;

      $test = preg_match('/^[A-Za-z]{2,255}$/', $last);
      if($test === false)
        $input_check = false;

      $test = preg_match('/^.*[@]{1}.*[.]{1}[a-z]{2,4}$/', $email);
      if($test === false)
        $input_check = false;

      if ($input_check) 
      {
        // commit user data
        $userData = array('first_name' => $first,'last_name' => $last,
                          'email' => $email, 'password' => $pass,'role_id' => 1);
        $user = new User($userData);
        $new_id = $user->commit_id();

        // commit membership data
        $memberData = array('user_id' => $new_id, 'expiration_date' => $expiry);
        $member = new Membership($memberData);
        $member->commit();

        $out = "$email account created, with expiry date $expiry";
      }
      else
      {
        //should never get here
        $out = 'Bad input.';
      }
    }
    else
    {
      //should never get here either
      $out = 'Missing Input Fields.';
    }
    echo "<p>$out</p>";
  }

DanceParty::render_view_with_template( 'create_member.php', array('members' => $members) );

?>