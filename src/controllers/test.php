<?php


require_once( DP_PLUGIN_DIR . 'models/users.php' );

User::create_table();
echo "Created table?";
echo "<h1>Rewrite works!</h1>";
?>
