<?php

require_once(DP_PLUGIN_DIR . 'class.danceparty.php');

/**
 * Router
 * Maps URLs to controller files.
 */
class Router
{
    static function register_routes() {
        include DP_PLUGIN_DIR . 'routes.php';
    }

    static function route( $endpoint, $controller ) {
        add_rewrite_rule( $endpoint . '/?$', 'index.php?dp_router_page=' . $controller, 'top' );
    }

    static function init_hooks() {
        add_filter( 'query_vars', array( 'Router', 'query_vars' ) );
        add_action( 'parse_request', array( 'Router', 'parse_request' ) );
    }

    static function query_vars( $query_vars ) {
        $query_vars[] = 'dp_router_page';
        return $query_vars;
    }

    static function parse_request( &$wp ) {
        if ( array_key_exists( 'dp_router_page', $wp->query_vars ) ) {
            include DanceParty::CONTROLLER_DIR . $wp->query_vars['dp_router_page'];
            exit();
        }
        return;
    }
}

?>
