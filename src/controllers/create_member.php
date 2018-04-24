<?php

require_once( DP_PLUGIN_DIR . 'class.authenticate.php' );
require_once(DP_PLUGIN_DIR . 'models/user.php');
require_once(DP_PLUGIN_DIR . 'models/roles.php');
require_once( DP_PLUGIN_DIR . 'models/tokens.php' );
require_once(DP_PLUGIN_DIR . 'helpers.php');


if ( ! Authenticate::is_logged_in() ) {
    wp_redirect(get_page_link(get_page_by_title('login')) . '?afterlog=create_member');
}

if ( ! Authenticate::is_admin() ) {
    die( 'Unauthorized' );
}

if ( isset( $_POST['create_member_nonce'] ) &&
    !wp_verify_nonce( $_POST['create_member_nonce'], 'submit' ) ) {
    die( 'Bad token' );
}


$error;
$info;
$viewParams = array(
    'first' => '',
    'last'  => '',
    'expiry'=> '',
);

if ( isset( $_POST['create_member_nonce'] ) ) {
    try {
        $first = valid_name(not_empty($_POST['first']));
        $last = valid_name(not_empty($_POST['last']));
        $email = valid_email(not_empty($_POST['email']));
        $expiry = valid_date(not_empty($_POST['expiry']));
        $pass = ''; // empty pass for admin-created members

        $users = User::query_users_from_email( $email );
        if ( count( $users ) !== 0 ) {
            throw new Exception( "$email already has associated account" );
        }

        $user = new User( array(
            'first_name' => $first,
            'last_name' => $last,
            'email' => $email,
            'password' => $pass,
            'role_id' => Role::$ROLE_IDS['MEMBER']
        ));
        $new_id = $user->commit_id();


        $member = new Membership( array(
            'user_id' => $new_id,
            'expiration_date' => $expiry
        ));
        $member->commit();

        /* Create a password reset token for the new user */
        $timespan = 15 ; // days till expiry
        $token = bin2hex(openssl_random_pseudo_bytes(Token::LENGTH));
        $expires = new DateTime('NOW');
        $expires->add(new DateInterval('P' . $timespan . 'D'));
        $expiration_date = $expires->format('Y-m-d H:i:s');
                
        // insert token into database (updates on duplicate reset request)
        $recovery_token = new Token( array(
        'user_id' => $new_id,
        'recovery_token' => $token,
        'expiration_date' => $expiration_date
        ));
        $recovery_token->commit();
        
        $link = get_page_link(get_page_by_title('reset password')) . '?token=' . $token;
        $subject = "Your new Contraborealis membership";
        $body = "Welcome $first, \n\n";
        $body .= "A new Contraborealis account with a membership expiring on $expiry has been created for you. \n\n";
        $body .= "Click the link below to set your password. \n\n";
        $body .= "<a href=$link> Set password</a>\n\n";
        $body .= "This link will expire in $timespan days.";
        send_email($email, $subject, $body);

        $info = "$email account created, with expiry date $expiry";
    }
    catch (Exception $e) {
        if ( get_class( $e ) !== BadInputException ) {
            error_log($e);
        }

        $error = $e->getMessage();

        if ( get_class($e) === PDOException) {
            $error = "Database error";
        }

        $viewParams['first'] = $first;
        $viewParams['last'] = $last;
        $viewParams['expiry'] = $expiry;
    }
}

$viewParams['error'] = $error;
$viewParams['info'] = $info;

DanceParty::render_view_with_template( 'create_member.php',
    $viewParams
);

?>
