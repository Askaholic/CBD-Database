<h1>Login to your Contra Borealis Dance account</h1>

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
