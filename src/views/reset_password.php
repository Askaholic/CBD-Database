<h1>Forgot your password?</h1>
<br>Enter the email address registered to your account and we'll send you a link you can use to pick a new password.<br>

<form method="post" action="">
<?php
    wp_nonce_field('submit', 'reset_nonce');
    FormBuilder::input( 'email', 'email', 'Email' );
    FormBuilder::input( 'new_password', 'new_password', 'New Password' );
    FormBuilder::input( 'confirm_password', 'confirm_password', 'Confirm Password' );
?>
    <input type="submit" value="Reset Password">
    <br><span id="error"></span><br>

</form>
