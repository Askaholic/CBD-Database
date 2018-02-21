
<?php

DanceParty::render_view_with_template('signup_view.php');


require_once(DP_PLUGIN_DIR . 'models/user.php');
require_once(DP_PLUGIN_DIR . 'class.passwordhash.php');


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
        if($test === false)
          $input_check = false;

        $test = preg_match('/^[A-Za-z]{2,255}$/', $last);
        if($test === false)
          $input_check = false;

        //email regex needs improved - needs to check user/domain chars
        $test = preg_match('/^.*[@]{1}.*[.]{1}[a-z]{2,4}$/', $email);
        if($test === false)
          $input_check = false;

        //password criteria should be more complex, must be mirrored in check() js function
        $test = preg_match('/^.{6,100}$/', $pass);
        if($test === false)
          $input_check = false;

        if($pass !== $pass2)
          $input_check = false;

        if($input_check)
        {
          $hash = Password::hash($pass);

          $userData = array('first_name' => $first,'last_name' => $last,
                            'email' => $email, 'password' => $hash,'role_id' => 1);
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
