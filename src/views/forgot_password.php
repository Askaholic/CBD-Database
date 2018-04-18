<script type="text/javascript">
	//TODO: Set up expiration for reset token, 15 mins
</script>

<form method="post" action="">
<p>Enter the email address affiliated with your account and we'll send you a link to reset your password.</p>
<?php
    wp_nonce_field( 'submit', 'forgot_nonce' );
    FormBuilder::input( 'email', 'email', 'Email' );
?>
    <input type="submit" value="Submit">
    <br><span id="error"></span><br>

</form>
