<?php

try {
    DanceParty::create_tables();
    $e = '';
} catch (PDOException $e) {
    $e = 'Error connecting to database. Please double check that the config options are correct.';
}

DanceParty::render_view( 'plugin_admin.php', array( 'error' => $e ) );

?>
