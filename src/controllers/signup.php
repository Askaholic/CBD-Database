
<?php

require_once(DP_PLUGIN_DIR . 'helpers.php');
require_once(DP_PLUGIN_DIR . 'models/user.php');
require_once(DP_PLUGIN_DIR . 'class.passwordhash.php');


if ( isset( $_POST['signup_nonce'] ) && !wp_verify_nonce( $_POST['signup_nonce'], 'submit' ) ) {
    die( 'Bad token' );
}

$error = '';

if ( isset( $_POST['signup_nonce'] ) ) {
    $first = $_POST['first'];
    $last = $_POST['last'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $pass2 = $_POST['confirm_password'];

    if ( empty($first) || empty($last) || empty($email) || empty($pass)) {
        die( 'Fields cannot be empty' );
    }

    $input_check = true;

    if( ! is_valid_name($first) ) {
        $input_check = false;
    }

    if( ! is_valid_name($last) ) {
        $input_check = false;
    }

    if( ! is_valid_email($email) )
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
        //password test
        // if(Password::verify($pass, $hash))
        //   $out = $out . 'password checks out';
        // else
        //   $out = $out . 'password not working';
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

echo "<div class='wrap'>$out</div>";

DanceParty::render_view_with_template('signup_view.php');

?>
