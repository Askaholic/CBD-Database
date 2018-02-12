<?php

require_once( 'model.php' );

class Role extends Model {
    const TABLE_NAME = 'roles';

    public static $columns = array(
        'id' => 'int NOT NULL PRIMARY KEY AUTO_INCREMENT',
        'name' => 'VARCHAR(45) NOT NULL'
    );
}
?>
