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

    protected static $columns = array();
    private $cols = array();

    public function __construct($arr) {
        AbstractConstantEnforcer::__add(__CLASS__, get_called_class());

        // Replace strings with column objects
        foreach ( static::$columns as $key => $value ) {
            $this->cols[$key] = new Column($value);
        }

        // Populate columns with values from arguments
        foreach ( $arr as $key => $value ) {
            $this->cols[$key]->value = $value;
        }
    }

    public function __get($key) {
        if (array_key_exists($key, $this->cols)) {
            return $this->cols[$key]->value;
        }
    }

    public function __set($key, $value) {

    }

    public static function create_table() {
        $columns_string = '';
        foreach (static::$columns as $name => $type) {
            $columns_string .= "$name $type,";
        }
        // Remove last comma
        $columns_string = substr($columns_string, 0, -1);
        self::query(
            "CREATE TABLE IF NOT EXISTS ? (
                $columns_string
            );",
            array(static::TABLE_NAME)
        );
    }

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
