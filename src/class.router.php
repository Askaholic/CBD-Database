<?php

require_once( DP_PLUGIN_DIR . 'class.danceparty.php' );
require_once( DP_PLUGIN_DIR . 'helpers.php' );

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

    static function route_post($name, $endpoint, $controller) {
        $post = get_page_by_title($name);

        preg_match('!(.*)[.]php!', $controller, $matches);
        $controller_name = $matches[1];

        $args_array = array(
            'post_title' => $name,
            'post_content' => "dp_page $controller_name",
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_name' => $endpoint,
            'guid' => "http://localhost/$endpoint",
            'post_type' => 'page'
        );

        // Update the page if it exists already
        if ($post !== null) {
            $args_array['ID'] = $post->ID;
        }
        $post_id = wp_insert_post( $args_array );

        if ( $post_id === 0 ) {
            throw new Exception("Error creating page '$endpoint'");
        }
    }

    static function init_hooks() {
        add_filter( 'query_vars', array( 'Router', 'query_vars' ) );
        add_action( 'parse_request', array( 'Router', 'parse_request' ) );
        add_filter( 'the_content', array( 'Router', 'embed_pages' ) , 9 );
    }

    static function embed_pages( $content ) {
        $pattern = '!dp_page ([a-z_]+)!';
        if (! preg_match( $pattern, $content, $matches )) {
            return $content;
        }

        $name = $matches[1];
        $output = DanceParty::capture_controller_output( "$name.php" );
        return preg_replace( $pattern, $output, $content);
    }

    static function query_vars( $query_vars ) {
        $query_vars[] = 'dp_router_page';
        return $query_vars;
    }

    static function parse_request( &$wp ) {
        if ( array_key_exists( 'dp_router_page', $wp->query_vars ) ) {
            DanceParty::run_controller($wp->query_vars['dp_router_page']);
            exit();
        }
        return;
    }
}

?>
