
<header>
  <title>Create member</title>
  <?php wp_head(); ?>
</header>

<body>
<h1 style="margin-left: 10px;">Create a new Contra Borealis Dance account</h1>

<form method="post" action="create_member">
  <?php wp_nonce_field('submit', 'create_member_nonce'); ?>
  <input type="text" name="user"
   pattern="[A-Za-z]{2,128} [A-Za-z]{2,128}" title="John Doe"
   placeholder="Enter First and Last Name" style="width: 300px; margin-left: 10px;">
  <input type="email" name="email"
    placeholder="Enter Email Address" style="width: 300px; margin-left: 10px;">
  <input type="date" name="expiry" style="width: 300px; margin-left: 10px;">
  <input type="submit" id="submit" value="Create Member" style="margin-left: 10px;">
</form>

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
      $pass = '';
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
?>

  <?php wp_footer(); ?>
</body>
