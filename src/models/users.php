<?php

require_once('pdo-repository.php');

/**
 * User
 */
class User extends Model {
    public $id;
    public $first_name;
    public $last_name;
    public $email;

    public static function create_table() {
        self::query(
            'CREATE TABLE users (
                id integer primary key,
                first_name varchar(128) not null,
                last_name varchar(128) not null,
                email varchar(255) not null
            );',
            array()
        );
    }

    public function push_update() {
        self::query(
            'INSERT INTO users VALUES (
                ?, ?, ?, ?
            );',
            array( $id, $first_name, $last_name, $email)
        );
    }

    public function pull_update() {

    }
}


?>
