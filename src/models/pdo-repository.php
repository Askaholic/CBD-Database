<?php

abstract class PDORepository {
    private static $username = 'cbd_dev_user';
    private static $password = 'cbd_dev_user_pass';
    private static $host = 'localhost';
    private static $name = 'cbd_dev';

    private static function get_connection() {
        $name = self::$name;
        $host = self::$host;
        $conn = new PDO( "mysql:dbname=$name;host=$host", self::$username, self::$password );
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        return $conn;
    }

    protected static function query( $sql, $args=array() ) {
        $conn = self::get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}

/**
 * Model base class
 */
abstract class Model extends PDORepository {
    public static abstract function create_table();
    public static abstract function query_all();
}

?>
