<?php 
require_once( DP_PLUGIN_DIR . 'models/user.php' );

$user = User::query_users_from_email( $_SESSION['usr']->email ); 

?>
<form method="post" action="">
<?php
    wp_nonce_field('submit', 'profile_nonce');
    FormBuilder::input('text', 'first', 'First Name', 'pattern="[A-Za-z]{2,128}" required title="First name, letters only" placeholder="' . $user[0]->first_name . '"');
    FormBuilder::input('text', 'last', 'Last Name', 'pattern="[A-Za-z]{2,128}" required title="Last Name, letters only" placeholder="' . $user[0]->last_name . '"');
    echo "<h4>Email Address</h4>";
    echo $user[0]->email;
    echo "<h4>Role</h4>";
    if ($user[0]->role_id === "1") echo "Member";
    if ($user[0]->role_id === "2") echo "Door Host";
    if ($user[0]->role_id === "3") echo "Admin";
?>
    </br>
    <input type="submit" value="Change Profile">
</form>
<input type="button" value="Change Password"onClick="document.location.href='forgot_password'" />