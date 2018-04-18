<?php
    require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
?>

<table>
    <tr>
        <th>First</th>
        <th>Last</th>
        <th>Email Address</th>
        <th>Expires</th>
        <th>Role</th>
    </tr>
<?php
$display_members = array();
foreach ( $context['members'] as $usr ) {
    $id = $usr->id;
    if ( array_key_exists( $id, $display_members ) ) {
        if ( $usr->expiration_date <= $display_members[$id]->expiration_date ) {
            continue;
        }
    }

    $display_members[$id] = $usr;
}
foreach ( $display_members as $usr ) { ?>
    <form method="post" action="" onsubmit="return confirm('Are you sure?')">
    <?php wp_nonce_field('submit', 'change_role_nonce'); ?>
    <tr>
        <td><?php echo $usr->first_name ?></td>
        <td><?php echo $usr->last_name ?></td>
        <td><?php echo $usr->email ?></td>
        <td><?php echo date_format(date_create($usr->expiration_date), 'M. j, Y') ?></td>
        <?php 
        if ( ! Authenticate::is_admin() ) {
            if ($usr->role_id === "1") echo "<td>Member</td>";
            if ($usr->role_id === "2") echo "<td>Door Host</td>";
            if ($usr->role_id === "3") echo "<td>Admin</td>";
        } 
        else { ?>
            <td style="width: 200px">
                <select name="role" style="width: 100px">
                    <option value="1" <?php if ($usr->role_id === "1") echo "selected"; ?>>Member</option>
                    <option value="2" <?php if ($usr->role_id === "2") echo "selected"; ?>>Door Host</option>
                    <option value="3" <?php if ($usr->role_id === "3") echo "selected"; ?>>Admin</option>
                </select>
                <input type="hidden" name="email" value="<?php echo $usr->email; ?>"/>
            </td>
            <td><input type="submit" id="submit" value="Change Role"></td>
            <td><a href="user/?user_id=<?php echo $usr->id; ?>">View User</a></td>
        <?php } ?>
    </tr>
    </form>
<?php } ?>
</table>
