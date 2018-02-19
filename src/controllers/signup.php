
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
      if(checkInputs())
      {
        document.getElementById("submit").disabled = false;
        document.getElementById("bad_input").innerHTML = "";
      }
      else
      {
        document.getElementById("bad_input").style.color = "red";
        document.getElementById("bad_input").innerHTML = "Missing input.";
      }
    }
  }

  function checkInputs()
  {
    if(!document.getElementById("first").value || !document.getElementById("last").value
        || !document.getElementById("email").value || !document.getElementById("password").value
        || !document.getElementById("confirm_password").value)
          return false; //empty input field

    return true;
  }
</script>
<?php


?>
<form method="post" action="signup">
  <?php wp_nonce_field('submit', 'signup_nonce'); ?>
  <input type="text" name="first" id="first" onkeyup="check();"
   pattern="[A-Za-z]{2,128}" title="First name"
   placeholder="Enter First Name" style="width: 300px; margin-left: 10px;">
   <input type="text" name="last" id="last" onkeyup="check();"
    pattern="[A-Za-z]{2,128}" title="Last name"
    placeholder="Enter Last Name" style="width: 300px; margin-left: 10px;">
  <input type="email" name="email" id="email" onkeyup="check();"
    pattern=".*[.]{1}[a-z]{2,4}" title="user@domain.com"
    placeholder="Enter Email Address" style="width: 300px; margin-left: 10px;">
  <input type="password" id="password" name="password" onkeyup="check();"
  pattern=".{5,20}" title="Password must be from 5 to 20 characters in length."
  placeholder="Choose Password" style="width: 300px; margin-left: 10px;">
  <input type="password" id="confirm_password" name="confirm_password" onkeyup="check();"
   placeholder="Confirm Password" style="width: 300px; margin-left: 10px;">
  <input type="submit" id="submit" value="Sign Up" style="margin-left: 10px;">
  <span id="bad_password"></span><br>
  <span id="bad_input"></span>
</form>

<?php

require_once(DP_PLUGIN_DIR . 'models/user.php');


    if(!isset($_POST['signup_nonce']) || !wp_verify_nonce($_POST['signup_nonce'], 'submit'))
    {
      //do nothing - either form hasn't been submitted or bad nonce
    }
    else //check form
    {
      $first = $_POST['first'];
      $last = $_POST['last'];
      $email = $_POST['email'];
      $pass = $_POST['password'];
      $pass2 = $_POST['confirm_password'];
      if(!empty($first) && !empty($last) && !empty($email) && !empty($pass))
      {
        $input_check = true;

        $test = preg_match('/^[A-Za-z]{2,255}$/', $first);
        if($test == false)
          $input_check = false;

        $test = preg_match('/^[A-Za-z]{2,255}$/', $last);
        if($test == false)
          $input_check = false;

        //email regex needs improved - needs to check user/domain chars
        $test = preg_match('/^.*[@]{1}.*[.]{1}[a-z]{2,4}$/', $email);
        if($test == false)
          $input_check = false;

        //password criteria should be more complex, must be mirrored in check() js function
        $test = preg_match('/^.{5,20}$/', $pass);
        if($test == false)
          $input_check = false;

        if($pass != $pass2)
          $input_check = false;

        if($input_check)
        {
          //this fails to commit - I think $ in hash breaks insert, quoting didn't help
          //TODO make it work or make external hashing class, wp hash isn't strong
          $hash = wp_hash_password($pass);
          //delete this out once sql insert special chars figured out
          echo "<p>$hash</p>";
          $hash = $pass;
          //

          $userData = array('first_name' => $first,'last_name' => $last,
                            'email' => $email, 'password' => $hash,'role_id' => 0);
          $user = new User($userData);
          $user->commit();

          $out = "$email account created";
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
?>

  <?php wp_footer(); ?>
</body>
