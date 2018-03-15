<?php

require_once( DP_PLUGIN_DIR . 'helpers.php' );

$sql_types = array(
    'text' => 'text',
    'number' => 'int',
    'radio' => '',
    'checkbox' => '',
    'select' => '',
);

$error;
$info;

if ( isset($_POST['event_schema']) ) {
    try {
        $field_info = json_decode(stripslashes($_POST['event_schema']), true);

        $event_name = not_empty(reg_chars($field_info['name']));
        $schema = array(
            'columns' => array_map( function($value) {
                    $col_name = not_empty(clean_name($value['name']));
                    $col_type = not_empty(clean_name($value['type']));

                    return "$col_name $col_type";
                }, $field_info['fields'] )
        );

        $schema1 = array(
            'columns' => array_map( function($value) {
                    $col_name = not_empty(clean_name($value['name']));
                    $col_type = not_empty(clean_name($value['type']));

                    return "$col_name $col_type";
                }, $field_info['fields'] )
        );

        require_once( DP_PLUGIN_DIR . 'models/event.php' );

        $event = new Event(array(
            'name' => $event_name,
            'enabled' => 0,
            'user_id' => 1, /* TODO: Make this the id of the logged in user */
            'schema_info' => json_encode($schema)
        ));

        if ($schema === $schema1) {
            throw new Exception( 'Cannot insert empty event' );
        }
        
        $event->commit();
    }
    catch (PDOException $e) {
        error_log($e);
        $error = 'Database error';
    }
    catch (Exception $e) {
        $error = $e->getMessage();
    }
    print_r($schema);
}


DanceParty::render_view_with_template( 'create_event.php',
    array(
        'error' => $error,
        'info' => $info
    )
);

?>
