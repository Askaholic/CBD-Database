<?php

require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );

if ( ! Authenticate::is_logged_in() ) {
    wp_redirect(get_page_link(get_page_by_title('login')) . '?afterlog=create_event');
}

$schema_types = array(
    'text' => 'text',
    'textarea' => 'text',
    'number' => 'int',
    'radio' => 'multivalued',
    'checkbox' => 'checkbox',
    'select' => 'multivalued',
    'userinfo' => 'userinfo',
    'eventdesc' => 'eventdesc',
    'childinfo' => 'childinfo',
    'payinfo' => 'payinfo',
);

$error;
$info;

if ( isset($_POST['event_schema']) ) {

    require_once( DP_PLUGIN_DIR . 'models/event.php' );

    try {
        $field_info = json_decode(stripslashes($_POST['event_schema']), true);
        $event_name = not_empty(reg_chars($field_info['name']));

        //TODO make these be numbers (already admin editable only)
        $event_cost = not_empty($field_info['costs']);

        $schema = array(
            'columns' => array_map( function($value) use( &$schema_types ) {
                    $col_name = not_empty(clean_name($value['name']));
                    $col_type = not_empty(clean_name($value['type']));
                    $col_desc = reg_chars($value['desc']);
                    $col_options = array_map( 'clean_name', $value['items'] );
                    $col_required = (bool) not_empty($value['required']);

                    if ( !is_string( $col_desc ) ) {
                        $col_desc = '';
                    }
                    $col_desc = htmlspecialchars( $col_desc );
                    $col_type = $schema_types[$col_type];

                    return array(
                        'name' => $col_name,
                        'type' => $col_type,
                        'description' => $col_desc,
                        'required' => $col_required,
                        'options' => $col_options,
                        'constraints' => ''
                    );
                }, $field_info['fields'] )
        );

        //push cost info into schema - doesn't match structure but cleaned up on show_event
        array_unshift( $schema['columns'], array( 'type' => 'costinfo', 'obj' => $event_cost ) );

        $empty_schema = array(
            'columns' => array()
        );
        if ( $schema === $empty_schema ) {
            throw new BadInputException( 'Cannot insert empty event' );
        }

        if ( Authenticate::is_logged_in() ) {
            $user_id = $_SESSION['usr']->id;
        }
        else {
            throw new Exception( 'User creating event not logged in' );
        }

        $event = new Event(array(
            'name' => $event_name,
            'enabled' => 0,
            'user_id' => $user_id,
            'schema_info' => json_encode($schema)
        ));

        $event->commit();
        $info = 'Event Submitted';
    }
    catch (Exception $e) {
        if ( get_class( $e ) !== BadInputException ) {
            error_log( $e );
        }

        $error = $e->getMessage();

        if ( get_class( $e ) === PDOException ) {
            $error = "Database error";
        }
    }
}


DanceParty::render_view_with_template( 'create_event.php',
    array(
        'error' => $error,
        'info' => $info
    )
);

?>
