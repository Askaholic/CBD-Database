<?php

abstract class PDORepository {
    private static $username = 'cbd_dev_user';
    private static $password = 'cbd_dev_user_pass';
    private static $host = 'localhost';
    private static $name = 'cbd_dev';

    private static $fetched_options = false;

    protected static function get_connection() {
        if ( !self::$fetched_options ) {
            self::$username = get_option( 'db_user' );
            self::$password = get_option( 'db_password' );
            self::$host = get_option( 'db_host' );
            self::$name = get_option( 'db_name' );
            self::$fetched_options = true;
        }

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

    protected static function query_id( $sql, $args=array() ) {
        $conn = self::get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
        return $conn->lastInsertId();
    }
}

/**
 * Model base class
 */
abstract class Model extends PDORepository {
    const TABLE_NAME = 'abstract';

    protected static $columns = array();
    private $cols = array();

    protected static $constraints = '';

    public function __construct($arr) {
        AbstractConstantEnforcer::__add(__CLASS__, get_called_class());

        // Replace strings with column objects
        foreach ( static::$columns as $key => $type ) {
            $this->cols[$key] = new Column($type);
        }

        // Populate columns with values from arguments
        foreach ( $arr as $key => $value ) {
            if ( !array_key_exists($key, $this->cols) ) {
                $this->cols[$key] = new Column('joined_value');
            }
            $this->cols[$key]->value = $value;
        }
    }

    protected function set_values($row) {
        foreach (static::$columns as $name => $type) {
            $this->cols[$name] = $row[$name];
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

    public static function get_sql_column_strings() {
        $column_names = '';
        $values_string = '';
        $update_columns_string = '';
        foreach (static::$columns as $name => $type) {
            $column_names .= "$name,";
            $values_string .= "?,";
            $update_columns_string .= "$name = VALUES($name),";
        }

        $column_names = substr($column_names, 0 , -1);
        $values_string = substr($values_string, 0 , -1);
        $update_columns_string = substr($update_columns_string, 0 , -1);

        return array(
            'names' => $column_names,
            'placeholders' => $values_string,
            'updates' => $update_columns_string
        );
    }

    public function pull() {
        $table = static::TABLE_NAME;

        $result = $this->query(
            "SELECT * FROM $table where id = ?;",
            array($this->id)
        );

        $this->set_values($result->fetch());
    }

    public function commit() {
        $table = static::TABLE_NAME;

        $sql_parts = self::get_sql_column_strings();

        $insert_columns_string = $sql_parts['names'];
        $values_string = $sql_parts['placeholders'];
        $update_columns_string = $sql_parts['updates'];

        $args = array();
        foreach (static::$columns as $name => $type) {
            array_push($args, $this->cols[$name]->value);
        }

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

    public function commit_id() {
        $table = static::TABLE_NAME;

        $sql_parts = self::get_sql_column_strings();

        $insert_columns_string = $sql_parts['names'];
        $values_string = $sql_parts['placeholders'];
        $update_columns_string = $sql_parts['updates'];

        $args = array();
        foreach (static::$columns as $name => $type) {
            array_push($args, $this->cols[$name]->value);
        }

        $id = $this->query_id(
            "INSERT INTO $table (
                $insert_columns_string
            ) VALUES (
                $values_string
            )
            ON DUPLICATE KEY UPDATE
                $update_columns_string;",
            $args
        );
        return $id;
    }

    public static function create_table() {
        $columns_string = '';
        foreach (static::$columns as $name => $type) {
            $columns_string .= "$name $type,";
        }
        // Remove last comma
        $columns_string = substr($columns_string, 0, -1);
        $table = static::TABLE_NAME;
        $query_string =
            "CREATE TABLE IF NOT EXISTS $table (
                $columns_string
            ";
        if ( defined(static::$constraints) and isset(static::$constraints) and static::$constraints != '' ) {
            $query_string .= ", $constraints";
        }
        $query_string .= ');';
        self::query( $query_string );
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
            $obj = self::create_instance_from_row($row);
            array_push($retval, $obj);
        }
        return $retval;
    }

    protected static function create_instance_from_row($row) {
        $column_values = array();
        foreach (static::$columns as $name => $type) {
            $column_values[$name] = $row[$name];
        }
        $obj = new static(
            $column_values
        );
        return $obj;
    }
    
    public function update($column1, $value1, $column2, $value2) {
        $table = static::TABLE_NAME;

        $this->query(
            "UPDATE $table 
             SET $column1 = '" .$value1. "'
             WHERE $column2 = '" .$value2."';"
        );
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
