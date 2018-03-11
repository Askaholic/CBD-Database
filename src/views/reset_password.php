<h1>Reset your password</h1>

<form method="post" action="">
    <?php
    wp_nonce_field('submit', 'password_nonce');
    FormBuilder::input( 'new_password', 'new_password', 'Password' );
    FormBuilder::input( 'confirm_password', 'confirm_password', 'Confirm Password' );
    ?>
    <br/>
    <input type="submit" value="Submit">

</form>
