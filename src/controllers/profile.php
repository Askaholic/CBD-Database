
<?php

/*
 * Require dependencies here
 */

require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );


/*
 * Keep this part if you need the user to be logged in
 */

if ( ! Authenticate::is_logged_in() ) {
    wp_redirect(get_page_link(get_page_by_title('login')) . '?afterlog=profile');
}

/*
 * Keep this part if you need to handle form data
 */

$nonce_name = 'profile_nonce';
if ( isset( $_POST[$nonce_name] ) && !wp_verify_nonce( $_POST[$nonce_name], 'submit' ) ) {
    die( 'Bad token' );
}

$error = '';
$info = '';

if ( isset( $_POST[$nonce_name] ) ) {
    try {
        /*
         * Get and validate arguments
         */
        $first = not_empty( $_POST['first'] );
        $last = not_empty( $_POST['last'] );

        $user = User::query_users_from_email( $_SESSION['usr']->email );
        /*
         * Do some logic
         */
        $user[0]->update('first_name', $first, 'email', $user[0]->email);

        $user[0]->update('last_name', $last, 'email', $user[0]->email);

        /*
         * Display a confirmation message
         */
        $info = "Successfully changed information";
    }
    catch ( Exception $e ) {
        if ( get_class( $e ) !== BadInputException ) {
            error_log($e);
        }
        /*
         * Display an error message if something goes wrong
         */
        $error = $e->getMessage();

        if ( get_class( $e ) === PDOException ) {
            $error = "Database error";
        }
    }
}

DanceParty::render_view_with_template( 'profile.php',
    array(
        'error' => $error,
        'info' => $info
    )
);

?>
