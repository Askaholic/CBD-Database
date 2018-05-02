<?php 
require_once( DP_PLUGIN_DIR . 'models/user.php' );

$user = User::query_users_from_id( $user_id );
if ( empty( $user ))
    die();

?>
<form method="post" action="" onsubmit="return confirm('Are you sure?')">
<?php
    wp_nonce_field('submit', 'user_nonce');
    FormBuilder::input('text', 'first', 'First Name', 'pattern="[A-Za-z]{2,128}" required title="First name, letters only" value="' . $user[0]->first_name . '"');
    FormBuilder::input('text', 'last', 'Last Name', 'pattern="[A-Za-z]{2,128}" required title="Last Name, letters only" value="' . $user[0]->last_name . '"');
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