<script type="text/javascript">
	//TODO: Set up expiration for reset toke, 15 mins
    function get15ahead() {
        var date = new Date();
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset() + 15);
        date.setFullYear(date.getFullYear());
        return date.toJSON().slice(0, 10);
    }
    document.onreadystatechange = () => {
        var newExpirationDate = get15ahead();

        if(document.readyState === 'complete' );
        var list = document.getElementsByClassName('default-date');
        for (let input of list) {
            input.value = newExpirationDate;
        }
    }
</script>

<h1>Reset Password</h1>

<form method="post" action="">
<?php
    wp_nonce_field('submit', 'forgot_nonce');
    FormBuilder::input( 'email', 'email', 'Email' );
?>
    <input type="submit" value="Submit">
    <br><span id="error"></span><br>

</form>
