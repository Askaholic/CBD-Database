<script type="text/javascript">
	//TODO: Set up expiration for reset token, 15 mins
</script>

<h1>Forgot Your Password?</h1>

<form method="post" action="send_link.php">
<p>Enter the email address affiliated with your account and we'll send you a link to reset your password.</p>
<?php
    wp_nonce_field('submit', 'forgot_nonce');
    FormBuilder::input( 'email', 'email', 'Email' );
?>
    <input type="submit" value="Submit">
    <br><span id="error"></span><br>

</form>
