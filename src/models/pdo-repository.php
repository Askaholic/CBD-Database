<?php

abstract class PDORepository {
    private $username = 'cbd_dev_user';
    private $password = 'cbd_dev_user_pass';
    private $host = 'localhost';
    private $name = 'cbd_dev';

    private function get_connection() {
        $conn = new PDO("mysql:dbname=$name;host=$host", $username, $password);
        return $conn;
    }

    protected function query($sql, $args) {
        $conn = $this->get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}

/**
 * Model
 */
abstract class Model extends PDORepository {
    public static abstract function create_table();
}

?>
