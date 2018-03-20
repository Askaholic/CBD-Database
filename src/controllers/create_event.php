<?php

require_once( DP_PLUGIN_DIR . 'helpers.php' );

$schema_types = array(
    'text' => 'text',
    'number' => 'int',
    'radio' => 'multivalued',
    'checkbox' => '',
    'select' => 'multivalued',
);

$error;
$info;

if ( isset($_POST['event_schema']) ) {

    require_once( DP_PLUGIN_DIR . 'models/event.php' );

    try {
        $field_info = json_decode(stripslashes($_POST['event_schema']), true);

        $event_name = not_empty(reg_chars($field_info['name']));

        $schema = array(
            'columns' => array_map( function($value) use( &$schema_types ) {
                    $col_name = not_empty(clean_name($value['name']));
                    $col_type = not_empty(clean_name($value['type']));
                    $col_desc = $value['desc'];
                    if ( !is_string($col_desc) ) {
                        $col_desc = '';
                    }
                    $col_desc = htmlspecialchars($col_desc);

                    // TODO: Handle checkbox
                    $col_type = $schema_types[$col_type];

                    return array(
                        'name' => $col_name,
                        'type' => $col_type,
                        'description' => $col_desc,
                        'constraints' => ''
                    );
                }, $field_info['fields'] )
        );

        // TODO: Make this an empty schema. This is still just copy and pasted
        // from above, so of course it's always going to be equal...
        $schema1 = array(
            'columns' => array_map( function($value) {
                    $col_name = not_empty(clean_name($value['name']));
                    $col_type = not_empty(clean_name($value['type']));

                    return "$col_name $col_type";
                }, $field_info['fields'] )
        );


        $event = new Event(array(
            'name' => $event_name,
            'enabled' => 0,
            'user_id' => 1, /* TODO: Make this the id of the logged in user */
            'schema_info' => json_encode($schema)
        ));

        // if ($schema === $schema1) {
        //     throw new Exception( 'Cannot insert empty event' );
        // }

        $event->commit();
        $info = 'Event Submitted';
    }
    catch (PDOException $e) {
        error_log($e);
        $error = 'Database error';
    }
    catch (Exception $e) {
        $error = $e->getMessage();
    }
}


DanceParty::render_view_with_template( 'create_event.php',
    array(
        'error' => $error,
        'info' => $info
    )
);

?>
