<h1><?php echo htmlspecialchars($event->name) ?></h1>
<?php

    //set up rows of invoice
    $rows['Invoice number'] = $event_inv['invoice'];
    $rows['Invoice amout'] = '$'.money_format( '%i', $user_inv->invoice_amount );
    $rows['Amount paid'] = '$'.money_format( '%i', $user_inv->amount_paid );

    foreach($event_inv as $key => $value)
    {
        if( $key === 'invoice' )
            continue;
        if( !is_int($key)) {
            //TODO make options values display T/F
            //unformat keys/names
            $key = str_replace('_', ' ', $key);
            $key = ucfirst($key);
            //same for value string
            if( is_string($value) ) {
                $value = str_replace('_', ' ', $value);
                $value = ucfirst($value);
            }
            $rows[$key] = $value;
        }
    }
?>

<table>
<?php foreach ($rows as $key => $value) { ?>

    <tr><th><?php echo $key ?></th><td><?php echo $value ?></td></tr><?php

    }?>
</table>
