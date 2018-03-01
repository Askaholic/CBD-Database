<h1>Create your Contra Borealis Dance account</h1>

<script>
    function checkPasswordsMatch() {
        let password = document.getElementById('password').value;
        let confirm_pass = document.getElementById('confirm_password').value;
        console.log(password, confirm_pass)
        if (password !== confirm_pass) {
            setPasswordMessage('Passwords do not match', 'red');
            return;
        }

        setPasswordMessage('');
    }

    function disableSubmit() {
        document.getElementsByName("submit")[0].disabled = true;
    }

    function setPasswordMessage(message, color='red') {
        let messageElement = document.getElementById("error");
        console.log(messageElement)
        messageElement.innerHTML = message;

        messageElement.style.color = color;
    }

</script>

<form method="post" action="signup">
<?php
    wp_nonce_field('submit', 'signup_nonce');
    FormBuilder::input('text', 'first', 'First Name', 'pattern="[A-Za-z]{2,128}" required title="Enter First Name"');
    FormBuilder::input('text', 'last', 'Last Name', 'pattern="[A-Za-z]{2,128}" required title="Enter Last Name"');
    FormBuilder::input('email', 'email', 'Email Address', 'pattern=".*[.]{1}[a-z]{2,4}" required title="Enter valid Email Address"');
    FormBuilder::input('password', 'password', 'Password', 'onkeyup="checkPasswordsMatch()" pattern=".{6,100}" required title="Password must be from 6 to 100 characters in length"');
    FormBuilder::input('password', 'confirm_password', 'Confirm Password', 'onkeyup="checkPasswordsMatch()" pattern=".{6,100}" required ');
?>

    <input type="submit" name="submit" value="Sign Up">
    <br><span id="error"></span><br>
</form>
