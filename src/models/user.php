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

class Membership extends Model {
    const TABLE_NAME = 'users_memberships';

    protected static $columns = array(
        'user_id' => 'int NOT NULL',
        'expiration_date' => 'DATE NOT NULL'
    );

    protected static $constraints = '
        PRIMARY KEY (user_id, expiration_date),
        FOREIGN KEY (user_id) REFERENCES users(id)
    ';
}

?>
