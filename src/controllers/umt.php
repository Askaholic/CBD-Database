<?php

require_once( DP_PLUGIN_DIR . 'models/user.php' );

class TestModel extends Model {

    const TABLE_NAME = 'test_model';

    protected static $columns = array(
        'id' => 'int',
        'name' => 'varchar(10)'
    );

    public static function query_all() {

    }
}

$tst = new TestModel(array('id' => 10, 'name' => 'ROhan'));



try {
    TestModel::create_table();
    echo "Table created successfully<br/>";
} catch(PDOException $e) {
    echo "Falied to create table: " . $e->getMessage();
}
//
// $user = new User('Rohan', 'Weeden', 'asdf@asdf.asdf');
// $user->sadf;

?>

<h1>User model test</h1>
