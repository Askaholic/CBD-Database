<?php

/*
 * Misc little helper functions that may be usefull throughout the plugin
 */

/**
 * Exception class for throwning exceptions that will not be logged to the error
 * log.
 */
class BadInputException extends Exception {}


function recurse_htmlspecialchars($input) {
    if ( is_array($input) ) {
        array_map("recurse_htmlspecialchars", $input);
        return;
    }
    elseif ( is_string($input) ) {
        return htmlspecialchars($input);
    }
}

/* Returns a copy of the input with all non alpha-numeric characters stripped out.
 * '_' and ' ' are not removed.
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
    if ($str == '' || empty($str) ) {
        throw new BadInputException("Invalid input: Cannot be empty.");
    }
    return $str;
}


function is_valid_name($name) {
    // Alpha numeric characters, length between 2-255
    return preg_match('/^[A-Za-z]{2,255}$/', $name) !== false;
}


function valid_name($name) {
    if (! is_valid_name($name) ) {
        throw new BadInputException("Invalid name");
    }
    return $name;
}


function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}


function valid_email($email) {
    if (! is_valid_email($email) ) {
        throw new BadInputException("Invalid email");
    }
    return $email;
}


function is_valid_password($pass) {
    //password criteria should be more complex, must be mirrored in check() js function
    return preg_match('/^.{6,100}$/', $pass) !== false;
}


function valid_password($pass) {
    if (! is_valid_password($pass) ) {
        throw new BadInputException("Invalid password");
    }
    return $pass;
}


function is_valid_date($date) {
    // yyyy-mm-dd
    return preg_match('/^[0-9]{4}-[0-9]-{2}[0-9]{2}$/', $date) !== false;
}


function valid_date($date) {
    if (! is_valid_date($date) ) {
        throw new BadInputException("Invalid date");
    }
    return $date;
}


function valid_bool($bool) {
    if (! is_bool($bool) ) {
        throw new BadInputException("Invalid boolean");
    }
    return $bool;
}


function is_valid_id($id) {
    return preg_match('/^[0-9]*$/', $id) !== false;
}


function valid_id($id) {
    if (! is_valid_id($id) ) {
        throw new BadInputException("Invalid id");
    }
    return $id;
}


?>
