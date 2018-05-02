<h1 style="margin-left: 10px;">Create a new Contra Borealis Dance account</h1>

<form method="post" action="">
<?php
    wp_nonce_field('submit', 'create_member_nonce');
    FormBuilder::input('text', 'first_name', 'First Name', 'pattern="[A-Za-z]{2,128}" required title="First name, letters only" value="' . $first . '"');
    FormBuilder::input('text', 'last_name', 'Last Name', 'pattern="[A-Za-z]{2,128}" required title="Last Name, letters only" value="' . $last . '"');
    FormBuilder::input('email', 'email', 'Email Address', 'required title="Enter valid email address"');
    FormBuilder::input('date', 'expiry', 'Expires', 'required value="' . $expiry . '"');
?>
    <input type="submit" value="Create Member">
</form>
