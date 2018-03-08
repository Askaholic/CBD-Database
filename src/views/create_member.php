<body>
<?php
if ( isset( $error ) ) {
?>
    <div class="error">
        <p><?php echo $error ?></p>
    </div>
<?php
}
?>
<?php
if ( isset( $info ) ) {
?>
    <div class="info">
        <p><?php echo $info ?></p>
    </div>
<?php
}
?>
<h1 style="margin-left: 10px;">Create a new Contra Borealis Dance account</h1>

<form method="post" action="create_member">
<?php
    wp_nonce_field('submit', 'create_member_nonce');
    FormBuilder::input('text', 'first', 'First Name', 'pattern="[A-Za-z]{2,128}" required title="First name, letters only"');
    FormBuilder::input('text', 'last', 'Last Name', 'pattern="[A-Za-z]{2,128}" required title="Last Name, letters only"');
    FormBuilder::input('email', 'email', 'Email Address', 'required title="Enter valid email address"');
    FormBuilder::input('date', 'expiry', 'Expires', 'required');
?>
    <input type="submit" value="Create Member">
</form>
