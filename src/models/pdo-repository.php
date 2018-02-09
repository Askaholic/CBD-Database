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
        echo $sql;
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
        foreach ( static::$columns as $key => $type ) {
            $this->cols[$key] = new Column($type);
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
        if (array_key_exists($key, $this->cols)) {
            $this->cols[$key] = $value;
        }
    }

    public function __toString() {
        $str = __CLASS__ . '( ';
        foreach ($this->cols as $name => $col) {
            $str .= "$name => '$col->value', ";
        }
        $str = substr($str, 0, -2);
        $str .= ')';
        return $str;
    }

    public function commit() {
        $table = static::TABLE_NAME;

        $insert_columns_string = '';
        $values_string = '';
        $update_columns_string = '';
        $args = array();
        foreach (static::$columns as $name => $type) {
            $insert_columns_string .= "$name,";
            $values_string .= "?,";
            $update_columns_string .= "$name = VALUES($name),";
            array_push($args, $this->cols[$name]->value);
        }
        $insert_columns_string = substr($insert_columns_string, 0 , -1);
        $values_string = substr($values_string, 0 , -1);
        $update_columns_string = substr($update_columns_string, 0 , -1);

        $this->query(
            "INSERT INTO $table (
                $insert_columns_string
            ) VALUES (
                $values_string
            )
            ON DUPLICATE KEY UPDATE
                $update_columns_string;",
            $args
        );
    }

    public static function create_table() {
        $columns_string = '';
        foreach (static::$columns as $name => $type) {
            $columns_string .= "$name $type,";
        }
        // Remove last comma
        $columns_string = substr($columns_string, 0, -1);
        $table = static::TABLE_NAME;
        self::query(
            "CREATE TABLE IF NOT EXISTS $table (
                $columns_string
            );"
        );
    }

    public static function query_all() {
        $table = static::TABLE_NAME;
        $columns_string = '';
        foreach (static::$columns as $name => $type) {
            $columns_string .= "$name,";
        }
        // Remove last comma
        $columns_string = substr($columns_string, 0, -1);

        $result = self::query(
            "SELECT $columns_string FROM $table;"
        );
        $retval = array();
        foreach ($result as $row) {
            $column_values = array();
            foreach (static::$columns as $name => $type) {
                $column_values[$name] = $row[$name];
            }
            $obj = new static(
                $column_values
            );
            array_push($retval, $obj);
        }
        return $retval;
    }
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
