<h1>Reset Password</h1>
<br>Enter your new password.<br>

<form method="post" action="">
<?php
    wp_nonce_field('submit', 'reset_nonce');
    FormBuilder::input( 'email', 'email', 'Email' );
    // TODO: Proper password input
    FormBuilder::input( 'password', 'new_password', 'New Password' );
    FormBuilder::input( 'password', 'confirm_password', 'Confirm Password' );
?>
    <br><input type="submit" value="Update Password"><br>
    <br><span id="error"></span><br>

</form>
