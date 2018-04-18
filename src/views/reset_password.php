Enter your new password.<br>

<form method="post" action="">
<?php
    wp_nonce_field( 'submit', 'reset_nonce' );
    FormBuilder::input( 'email', 'email', 'Email' );
    FormBuilder::input( 'password', 'new_password', 'New Password', 'onkeyup="checkPasswordsMatch()" pattern=".{6,100}" required title="Password must be between 6-100 characters" value="' . $newpass . '"');
    FormBuilder::input( 'password', 'confirm_password', 'Confirm Password', 'onkeyup="checkPasswordsMatch()" pattern=".{6,100}" required value="' . $newpass2 . '"');
?>
    <br><input type="submit" value="Update Password"><br>
    <br><span id="error"></span><br>

</form>
