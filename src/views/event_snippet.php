<h1><?php echo htmlspecialchars($event->name) ?></h1>
<script>
//TODO move into show_event - snippet shouldn't be interactive
function updateCost(c) {
    var child = parseFloat(document.getElementById('children').value);
    var young_adult = parseFloat(document.getElementById('young_adults').value);
    var adult = parseFloat(document.getElementById('adults').value);
    let cost = parseFloat(c.user) + parseFloat(c.child)*child + parseFloat(c.young_adult)*young_adult + parseFloat(c.adult)*adult;
    document.getElementById('payment').innerHTML="Total amount due: $"+cost;
    document.getElementById('total_due').value=cost;
}
</script>
<?php
$cols = (array) json_decode($event->schema_info);

foreach($cols as $fields) {
    foreach ($fields as $field) {
        $name =  $field->name;
        $type = $field->type;
        if($type === "costinfo") {
            $obj = $field->obj;

            $coststring = "{ user : $obj->user, "
                        . "child : $obj->child, "
                        . "young_adult : $obj->young_adult, "
                        . "adult : $obj->adult }";

            $jsfnstr = "updateCost($coststring); ";
            $cost = floatval($obj->user);
            continue;
        }
        if( $type === "int" )
            $type = "number";
        $extra = '';
        if($field->required)
            $extra = 'required';
        $description = $field->description;
        if( $type === 'eventdesc' ) {
            FormBuilder::eventDescription($description);
        }
        else if ( $type === 'userinfo') {
            FormBuilder::userInfoForm($user);
        }
        else if ( $type === 'childinfo') {
            FormBuilder::childInfoForm($jsfnstr);
        }
        else if ( $type === 'payinfo') {
            FormBuilder::paymentInfoForm($cost);
        }
        else {
            FormBuilder::input($type, $name, $description, $extra);
        }
    }
}
FormBuilder::input('hidden', 'total_due', '', 'value="' . "$cost" . '"');
?>
