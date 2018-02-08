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
$check = $_POST['menu'];
if(!empty($check))
  $menu=$check;

echo "<iframe src='$menu' width='100%' height='50%'></iframe>";

?>
<hr>
  <h3>Footer</h3>
  <?php wp_footer(); ?>
</body>
