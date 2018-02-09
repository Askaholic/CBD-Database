<?php

require_once('pdo-repository.php');

/**
 * User
 */
class User extends Model {
    const TABLE_NAME = 'users';

    protected static $columns = array(
        'id' => 'int PRIMARY KEY NOT NULL AUTO_INCREMENT',
        'first_name' => 'VARCHAR(128) NOT NULL',
        'last_name' => 'VARCHAR(128) NOT NULL',
        'email' => 'VARCHAR(255) NOT NULL'
    );
}

?>
