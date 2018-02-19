<h1>Expired Members</h1>

<table>
    <tr>
        <th>First</th>
        <th>Last</th>
        <th>Expires</th>
        <th>Renew</th>
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
<form method="post" action="show_expired_users">
<?php wp_nonce_field('submit', 'renew_member_nonce'); ?>
    <tr>
        <td><?php echo $usr->first_name ?></td>
        <td><?php echo $usr->last_name ?></td>
        <td><?php echo date_format(date_create($usr->expiration_date), 'M. j, Y') ?></td>
        <td style="width: 200px">
            <input type="date" name="expiry">
            <input type="hidden" name="id" value="<?php $usr->id; ?>"/>
        </td>
        <td><input type="submit" id="submit" value="Renew"></td>
    </tr>
</form>
<?php } ?>
</table>
