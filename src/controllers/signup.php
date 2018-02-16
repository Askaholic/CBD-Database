
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
    //do wp_nonce()
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
  <!--?php wp_nonce(); ?-->
  <input type="text" name="user"
   pattern="[A-Za-z]{2,128} [A-Za-z]{2,128}" title="John Doe"
   placeholder="Enter First and Last Name" style="width: 300px; margin-left: 10px;">
  <input type="email" name="email"
    placeholder="Enter Email Address" style="width: 300px; margin-left: 10px;">
  <input type="password" id="password" name="password" onkeyup="check();"
  pattern="*{5,20}" title="Password must be from 5 to 20 characters in length."
  placeholder="Choose Password" style="width: 300px; margin-left: 10px;">
  <input type="password" id="confirm_password" name="confirm_password" onkeyup="check();"
   placeholder="Confirm Password" style="width: 300px; margin-left: 10px;">
  <input type="submit" id="submit" value="Sign Up" style="margin-left: 10px;">
  <span id="bad_password"></span>
</form>


<?php

require_once(DP_PLUGIN_DIR . 'models/user.php');

    $name = $_POST['user'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $pass2 = $_POST['confirm_password'];
    if(!empty($name) && !empty($email) && !empty($pass))
    {
      if($pass == $pass2)
      {
    //    $hash = wp_hash_password($pass);
        $hash = "test";
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

?>

  <?php wp_footer(); ?>
</body>
