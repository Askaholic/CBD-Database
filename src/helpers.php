<?php

/*
 * Misc little helper functions that may be usefull throughout the plugin
 */


function reg_chars($name) {
    $newName = preg_replace('/[^a-zA-Z0-9_ ]+/', '', $name);
    return $newName;
}

/* Makes the string lowercase, replaces spaces with underscores, and removes
    all non alpha numeric characters */
function clean_name($name) {
    $newName = strtolower($name);
    $newName = preg_replace('/[ ]+/', '_', $newName);
    $newName = reg_chars($newName);
    return $newName;
}

/* Returns the string, throws an exception if it is empty */
function not_empty($str) {
    if ($str == '') {
        throw new Exception("Invalid input: Cannot be empty.");
    }
    return $str;
}

?>
