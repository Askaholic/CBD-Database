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
    const TABLE_NAME = 'abstract';

    protected $columns = array();

    public function __construct() {
        AbstractConstantEnforcer::__add(__CLASS__, get_called_class());
    }

    public function __get($key) {
        echo '__get called';
        if (array_key_exists($key, $this->$columns)) {
            return $this->$columns[$key]->$value;
        }
    }

    public function __set($key, $value) {
        
    }

    public static abstract function create_table();
    public static abstract function query_all();
}

class Column {
    public $type;
    public $value;

    function __construct($type) {
        $this->type = $type;
    }
}

class AbstractConstantEnforcer {
    public static function __add($class, $c) {
        $reflection = new ReflectionClass($class);
        $constantsForced = $reflection->getConstants();
        foreach ($constantsForced as $constant => $value) {
            if (constant("$c::$constant") == 'abstract') {
                throw new Exception("Undefined constant '$constant' in " . (string) $c);
            }
        }
    }
}

?>
