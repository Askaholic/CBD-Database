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
?>

<h3>Session Test</h3>

<?php

require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );

  echo "Logged in: ";
  print_r(Authenticate::is_logged_in());
  echo "\n ID: " .$_SESSION['id']. "\nRole: " .$_SESSION['role'];
  echo "\nAdmin?: ";
  print_r(Authenticate::is_admin());
  echo "\nDoor Host?: ";
  print_r(Authenticate::is_door_host());

?>

<h3>Email Test</h3>

<?php

require_once(DP_PLUGIN_DIR . 'helpers.php');

  echo "Testing email functionality... \n";

  $address = "example@email.com";
  $subject = "Hello!";
  $body = "Email message body";

  if (send_email($address, $subject, $body)) {
    echo "Email sent successfully \n";
  } else {
    echo "Email failed to send \n";
  }
?>


<hr>
  <h3>Footer</h3>
  <?php wp_footer(); ?>
</body>
