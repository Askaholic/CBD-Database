<h1><?php echo htmlspecialchars($event->name) ?></h1>
<?php
$cols = (array) json_decode($event->schema_info);

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
