
<header>
  <title>Sign up</title>
  <?php wp_head(); ?>
</header>

<body>
<h1 style="margin-left: 10px;">Create your Contra Borealis Dance account</h1>

<script>
//can put other limits on password here
  function check()
  {
    if(!document.getElementById("password").value)
    {
      document.getElementById("submit").disabled = true;
      document.getElementById("bad_password").innerHTML = "";
    }
    else if(document.getElementById("password").value != document.getElementById("confirm_password").value)
    {
      document.getElementById("submit").disabled = true;
      document.getElementById("bad_password").style.color = "red";
      document.getElementById("bad_password").innerHTML = "Passwords don't match.";
    }
    else
    {
      document.getElementById("bad_password").style.color = "green";
      document.getElementById("bad_password").innerHTML = "    Password good.";
      document.getElementById("submit").disabled = false;
    }
  }
</script>
<?php


?>
<form method="post" action="signup">
  <?php wp_nonce_field('submit', 'signup_nonce'); ?>
  <input type="text" name="user"
   pattern="[A-Za-z]{2,128} [A-Za-z]{2,128}" title="John Doe"
   placeholder="Enter First and Last Name" style="width: 300px; margin-left: 10px;">
  <input type="email" name="email"
    placeholder="Enter Email Address" style="width: 300px; margin-left: 10px;">
  <input type="password" id="password" name="password" onkeyup="check();"
  pattern=".{5,20}" title="Password must be from 5 to 20 characters in length."
  placeholder="Choose Password" style="width: 300px; margin-left: 10px;">
  <input type="password" id="confirm_password" name="confirm_password" onkeyup="check();"
   placeholder="Confirm Password" style="width: 300px; margin-left: 10px;">
  <input type="submit" id="submit" value="Sign Up" style="margin-left: 10px;">
  <span id="bad_password"></span>
</form>


<?php

require_once(DP_PLUGIN_DIR . 'models/user.php');


    if(!isset($_POST['signup_nonce']) || !wp_verify_nonce($_POST['signup_nonce'], 'submit'))
    {
      //do nothing - either form hasn't been submitted or bad nonce
    }
    else //check form
    {
      $name = $_POST['user'];
      $email = $_POST['email'];
      $pass = $_POST['password'];
      $pass2 = $_POST['confirm_password'];
      if(!empty($name) && !empty($email) && !empty($pass))
      {
        if($pass == $pass2)
        {
          //this fails to commit - I think $ in hash breaks insert, quoting didn't help
          //TODO make it work or make external hashing class, wp hash isn't strong
          $hash = wp_hash_password($pass);
          //delete this out once sql insert special chars figured out
          echo "<p>$hash</p>";
          $hash = $pass;
          //

          $splitName = explode(" ", $name);
          $userData = array('first_name' => $splitName[0],'last_name' => $splitName[1],
                            'email' => $email, 'password' => $hash,'role_id' => 0);
          $user = new User($userData);
          $user->commit();

          $out = "$email account created";
        }
        else
        {
          //should never get here
          $out = "Passwords do not match";
        }
      }

      echo "<p>$out</p>";
    }
?>

  <?php wp_footer(); ?>
</body>
