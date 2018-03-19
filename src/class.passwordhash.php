<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//using php default hash functions
// http://php.net/manual/en/function.password-hash.php

class Password
{
    public static function hash($pass) {
        return password_hash( $pass, PASSWORD_DEFAULT );
    }

    public static function verify($unhashed, $hashed) {
        if ( $hashed === '' || !is_string( $hashed ) || !is_string( $unhashed )) {
            return false;
        }

        return password_verify( $unhashed, $hashed );
    }
}

?>
