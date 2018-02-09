<?php

require_once( DP_PLUGIN_DIR . 'models/user.php' );

class TestModel extends Model {
    const TABLE_NAME = 'test_model';

    protected static $columns = array(
        'id' => 'int',
        'name' => 'varchar(10)'
    );
}

$tst = new TestModel(array('id' => 10, 'name' => 'ROhan'));
$tst->commit();


try {
    TestModel::create_table();
    echo "Table created successfully<br/>";
} catch(PDOException $e) {
    echo "Falied to create table: " . $e->getMessage();
}

$users = User::query_all();
foreach ($users as $usr) {
    echo strval($usr) . '<br/>';
}
//
// $user = new User('Rohan', 'Weeden', 'asdf@asdf.asdf');
// $user->sadf;

?>

<h1>User model test</h1>
