<h1>Create your Contra Borealis Dance account</h1>

<form method="post" action="">
<?php
    wp_nonce_field('submit', 'signup_nonce');
    FormBuilder::input('text', 'first_name', 'First Name', 'pattern="[A-Za-z]{2,128}" required title="First name, letters only"');
    FormBuilder::input('text', 'last_name', 'Last Name', 'pattern="[A-Za-z]{2,128}" required title="Last Name, letters only"');
    FormBuilder::input('email', 'email', 'Email Address', 'pattern=".*[.]{1}[A-Za-z]{2,4}" required title="Enter valid email address"');
    FormBuilder::input('password', 'password', 'Password', 'onkeyup="checkPasswordsMatch()" pattern=".{6,100}" required title="Password must be between 6-100 characters"');
    FormBuilder::input('password', 'confirm_password', 'Confirm Password', 'onkeyup="checkPasswordsMatch()" pattern=".{6,100}" required ');
?>

    <input type="submit" name="submit" value="Sign Up">
    <br><span id="error"></span><br>
</form>
