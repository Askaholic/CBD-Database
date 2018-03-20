<?php

$error;
$info;

try {
    DanceParty::create_tables();
    $info = 'Settings updated successfully';
} catch (PDOException $e) {
    error_log($e);
    $error = 'Error connecting to database. Please double check that the config options are correct.';
}

DanceParty::render_view(
    'plugin_admin.php',
    array(
        'error' => $error,
        'info' => $info
    )
);

?>
