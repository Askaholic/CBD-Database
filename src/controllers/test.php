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

<?php
require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );

	$GLOBALS['session']->set( 'id', '1234' );

//echo Authenticate()->logged_in();
	$id = $GLOBALS['session']->get( 'id' );
	if(empty($id))
		echo 'No session id found.';
	else
		echo 'Session id: ' . $GLOBALS['session']->get( 'id' );

?>

<hr>
  <h3>Footer</h3>
  <?php wp_footer(); ?>
</body>
