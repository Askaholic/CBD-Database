<script type="text/javascript">
    function getDateOneYearFromToday() {
        var date = new Date();
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
        date.setFullYear(date.getFullYear() + 1);
        return date.toJSON().slice(0, 10);
    }
    document.onreadystatechange = () => {
        var newExpirationDate = getDateOneYearFromToday();

        if(document.readyState === 'complete' );
        var list = document.getElementsByClassName('default-date');
        for (let input of list) {
            input.value = newExpirationDate;
        }
    }
</script>

<h1>Inactive Members</h1>

<table>
    <tr>
        <th>First</th>
        <th>Last</th>
        <th>Email Address</th>
        <th>Expires</th>
        <th>Renew</th>
    </tr>
    <?php
    foreach ( $members as $usr ) { ?>
    <form method="post" action="" onsubmit="return confirm('Are you sure?')">
    <?php wp_nonce_field('submit', 'renew_member_nonce'); ?>
        <tr>
            <td><?php echo $usr->first_name ?></td>
            <td><?php echo $usr->last_name ?></td>
            <td><?php echo $usr->email ?></td>
            <td><?php echo date_format(date_create($usr->expiration_date), 'M. j, Y') ?></td>
            <td style="width: 200px">
                <input class="default-date" type="date" name="expiry">
                <input type="hidden" name="id" value="<?php echo $usr->id; ?>"/>
            </td>
            <td><input type="submit" id="submit" value="Renew"></td>
        </tr>
    </form>
    <?php } ?>
</table>
