
<form method="post" action="">
    <?php
    wp_nonce_field( 'submit', 'login_nonce' );
    if( not_empty( $afterlog ) ) {
        FormBuilder::input( 'hidden', 'afterlog', '', 'value=' . $afterlog );
    }
    FormBuilder::input( 'email', 'email', 'Email' );
    FormBuilder::input( 'password', 'password', 'Password' );
    ?>
    <br/>
    <input type="submit" value="Login">
</form>
<h3>Don't have an account yet?</h3>
<input type="button" value="Sign Up"onClick="document.location.href='signup'" />
