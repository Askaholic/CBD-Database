<h1>Current Members</h1>

<table>
    <tr>
        <th>First</th>
        <th>Last</th>
    </tr>
<?php foreach ($context['members'] as $usr) { ?>
    <tr>
        <td><?php echo $usr->first_name ?></td>
        <td><?php echo $usr->last_name ?></td>
    </tr>
<?php } ?>
</table>
