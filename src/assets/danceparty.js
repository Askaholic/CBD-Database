function checkPasswordsMatch() {
    let password = document.getElementById('password').value;
    let confirm_pass = document.getElementById('confirm_password').value;

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

    messageElement.innerHTML = message;
    messageElement.style.color = color;
}
