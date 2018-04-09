<?php

require_once( 'model.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );

/**
 * Event. Table for keeping track of custom event pages.
 * Each Event object will create another table in the database with the
 * schema specified in the schema_info field. This table will keep track of
 * all users registered for that event and their form inputs.
 */
class Event extends Model
{
    const TABLE_NAME = 'events';

    protected static $columns = array(
        'id' => 'int PRIMARY KEY NOT NULL AUTO_INCREMENT',
        'name' => 'VARCHAR(255) NOT NULL',
        'enabled' => 'boolean NOT NULL DEFAULT 0',
        'user_id' => 'int NOT NULL',
        'schema_info' => 'JSON'
    );
    protected static $constraints = '
        FOREIGN KEY (user_id) references users(id)
    ';

    public function create() {
        $schema = json_decode($this->schema_info);

        $table_name = $this->id . '_' . clean_name($this->name);
        $column_strings = implode(',', $schema->columns);
        $column_string = json_schema_to_column_string( $schema );

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            $column_strings
        );";
        self::query($sql);
    }

    public static function query_events_from_id($id) {
      $event_table = Event::TABLE_NAME;
      $result = self::query(
        "SELECT * FROM $event_table WHERE id = '$id';"
      );
      //id is primary key so there can be only one
      foreach($result as $row)
        $event = Event::create_instance_from_row($row);
      return $event;

    }
}

/**
 * ScheduledEvent. Table for keeping track of when events are scheduled
 */
class ScheduledEvent extends Model
{
    const TABLE_NAME = 'scheduled_events';

    protected static $columns = array(
        'id' => 'int NOT NULL PRIMARY KEY AUTO_INCREMENT',
        'event_id' => 'int NOT NULL',
        'start_date' => 'date NOT NULL',
        'end_date' => 'date NOT NULL'
    );
    protected static $constraints = '
        FOREIGN KEY (event_id) references events(id)
    ';

    public static function query_events_from_id($id) {
      $event_table = ScheduledEvent::TABLE_NAME;
      $result = self::query(
        "SELECT * FROM $event_table WHERE id = '$id';"
      );
      //id is primary key so there can be only one
      foreach($result as $row)
        $event = ScheduledEvent::create_instance_from_row($row);
      return $event;

    }
}

function json_schema_to_column_string( $schema ) {
    return implode(
        ',',
        array_map(
            'json_column_to_string',
            $schema->columns
        )
    );
}

function json_column_to_string( $column ) {
    $name = $column->name;
    $constraints = $column->constraints;
    $required = $column->required ? ' not null ' : '';

    $type = $column->type;
    if ( $type === 'multivalued' ) {
        $type = 'varchar(255)';
    }
    else if ( $type === 'checkbox' ) {
        $cols = array();
        foreach ( $column->options as $i => $value ) {
            array_push( $cols, "$value BOOLEAN $required $constraints" );
        }

        return implode( ',', $cols );
    }

    return "$name $type $required $constraints";
}

?>
