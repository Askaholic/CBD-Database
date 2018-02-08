<?php

require_once('pdo-repository.php');

/**
 * User
 */
class User extends Model {
    /* Used to identify the table entry for this object */
    private $_id;

    /* Only available in case the client needs to read the id */
    public $id;
    public $first_name;
    public $last_name;
    public $email;

    public static function create_table() {
        self::query(
            'CREATE TABLE IF NOT EXISTS users (
                id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
                first_name VARCHAR(128) NOT NULL,
                last_name VARCHAR(128) NOT NULL,
                email VARCHAR(255) NOT NULL
            );',
            array()
        );
    }

    public static function query_all() {
        $result = self::query(
            'SELECT * FROM users;'
        );
        $users = array();
        foreach ($result as $row) {
            $usr = new User(
                $row['first_name'],
                $row['last_name'],
                $row['email']
            );
            $usr->id = $row['id'];
            array_push($users, $usr);
        }
        return $users;
    }

    function __construct($first_name, $last_name, $email) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
    }

    public function push_update() {
        $this->query(
            'INSERT INTO users (
                id,
                first_name,
                last_name,
                email
            ) VALUES (
                ?, ?, ?, ?
            )
            ON DUPLICATE KEY UPDATE
                first_name = VALUES(first_name),
                last_name = VALUES(last_name),
                email = VALUES(email)
                ;',
            array(
                $this->id,
                $this->first_name,
                $this->last_name,
                $this->email
            )
        );
    }

    public function pull_update() {

    }
}


?>
