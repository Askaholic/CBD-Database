<?php

require_once('pdo-repository.php');

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
        'name' => 'VARCHAR(128) NOT NULL',
        'enabled' => 'boolean NOT NULL DEFAULT 0',
        'schema_info' => 'JSON'
    );
}


?>
