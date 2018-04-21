<h1><?php echo htmlspecialchars($event->name) ?></h1>
<script>
function updateCost(c) {
    var child = parseFloat(document.getElementById('children').value);
    var young_adult = parseFloat(document.getElementById('young_adults').value);
    var adult = parseFloat(document.getElementById('adults').value);
    let cost = parseFloat(c.user) + parseFloat(c.child)*child + parseFloat(c.young_adult)*young_adult + parseFloat(c.adult)*adult;
    document.getElementById('payment').innerHTML="Total amount due: $"+cost;
    document.getElementById('total_due').value=cost;
}
</script>

<style>
.border-light {
    border: 1px solid grey;
    border-radius: .2rem;
    padding: 1rem;
}
</style>

<?php
$cols = (array) json_decode($event->schema_info);

foreach($cols as $fields) {
    foreach ($fields as $field) {
        echo '<div class="border-light">';
        $name =  $field->name;
        $type = $field->type;
        if($type === 'costinfo') {
            $obj = $field->obj;

            $coststring = "{ user : $obj->user, "
                        . "child : $obj->child, "
                        . "young_adult : $obj->young_adult, "
                        . "adult : $obj->adult }";
            $child_cost = floatval($obj->child);
            $young_adult_cost = floatval($obj->young_adult);
            $adult_cost = floatval($obj->adult);
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
            if( $type === 'checkbox' || $type === 'multivalued') {
                echo "<b>$name</b><br>";
                echo $description;
                foreach($field->options as $option)
                {
                    if( $type === 'multivalued') $type = 'radio';
                    FormBuilder::input($type, $option, $option); //TODO required options
                }
            }
            else {
                echo "<b>$name</b><br>";
                FormBuilder::input($type, $name, $description, $extra);
            }
        }
        echo '</div>';
    }
}
FormBuilder::input('hidden', 'total_due', '', 'value="' . "$cost" . '"');
FormBuilder::input('hidden', 'member_cost', '', 'value="' . "$member_cost" . '"');
FormBuilder::input('hidden', 'child_cost', '', 'value="' . "$child_cost" . '"');
FormBuilder::input('hidden', 'young_adult_cost', '', 'value="' . "$young_adult_cost" . '"');
FormBuilder::input('hidden', 'adult_cost', '', 'value="' . "$adult_cost" . '"');
?>
