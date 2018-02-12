<?php

require_once( 'model.php' );

class Role extends Model {
    const TABLE_NAME = 'roles';

    public static $columns = array(
        'id' => 'int NOT NULL PRIMARY KEY AUTO_INCREMENT',
        'name' => 'VARCHAR(45) NOT NULL'
    );

    public static $roles = array(
        array(
            'id' => 1,
            'name' => 'MEMBER'
        ),
        array(
            'id' => 2,
            'name' => 'DOOR_HOST'
        ),
        array(
            'id' => 3,
            'name' => 'ADMIN'
        )
    );

    public static function create_table() {
        parent::create_table();

        $table = static::TABLE_NAME;
        $sql_parts = self::get_sql_column_strings();

        $columns = $sql_parts['names'];
        $placeholders = $sql_parts['placeholders'];
        $updates = $sql_parts['updates'];

        $all_placeholders = implode(
            ', ',
            array_fill(
                0,
                count(static::$roles),
                '(' . $placeholders . ')')
        );
        $args = array_reduce(
            array_map(
                function($role) {
                    return array($role['id'], $role['name']);
                },
                static::$roles ),
                'array_merge',
                array()
        );
        $conn = self::get_connection();
        $sql = "INSERT INTO $table (
                $columns
            ) VALUES
                $all_placeholders
            ON DUPLICATE KEY UPDATE
                $updates;";

        $conn->beginTransaction();
        $stmt = $conn->prepare( $sql );
        $stmt->execute( $args );
        $conn->commit();
    }
}
?>
