<header>
  <title>test signup form</title>
  <?php wp_head(); ?>
</header>

<body>
  <h1>Test Page - has template hooks</h1><hr>
  <div>
    <h3>Menu</h3>
    <?php wp_meta(); ?>
  </div>
  <hr>
  <h3>Custom Menu</h3>
<!-- //custom hooks example -->
<?php do_action('wp_iframe');

$menu = "index.php/home";
if (isset($_POST['menu']) && !empty($_POST['menu'])) {
    $check = $_POST['menu'];
    if(!empty($check))
      $menu=$check;
}

echo "<iframe src='$menu' width='100%' height='50%'></iframe>";

require_once( DP_PLUGIN_DIR . 'models/users.php' );

try {
    User::create_table();
    echo "Table created successfully<br/>";
} catch(PDOException $e) {
    echo "Falied to create table: " . $e->getMessage();
}
$user = new User('Rohan', 'Weeden', 'asdf@asdf.asdf');
$user->push_update();
$all_users = User::query_all();
echo 'All users in table: <br/>';
foreach ($all_users as $user) {
    echo $user->first_name . ' ' . $user->last_name  . ' ' . $user->email . '<br/>' ;
}
?>
<hr>
  <h3>Footer</h3>
  <?php wp_footer(); ?>
</body>
