<?php

//debug
// echo "event found! <br>";
// $s = count( $events );
// $json = $events[0]->schema_info;
// echo "$json <br><br>";
$title = $event->name;
echo "<h1>$title</h1>";
$cols = (array) json_decode($event->schema_info);
?>
<form method="post" action="">
<?php
wp_nonce_field('submit', 'event_nonce');
foreach($cols as $fields) {
    foreach ($fields as $field) {
        $name =  $field->name;
        $type = $field->type;
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
        else {
            FormBuilder::input($type, $name, $description, $extra);
        }
    }
}
?>
<!-- Should button say Join Event or something less generic than Submit? -->
<input type="submit" name="submit" value="Submit">
</form>
<?php

 ?>
