<?php

require_once(DP_PLUGIN_DIR . 'class.danceparty.php');
require_once(DP_PLUGIN_DIR . 'helpers.php' );

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
            'post_content' => "page $controller_name",
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
        add_filter( 'the_content', array( 'Router', 'render_php' ) , 9 );
    }

    static function render_php($content) {
        if (! preg_match('!page (.*)!', $content, $matches)) {
            return $content;
        }

        $file = clean_name($matches[1]);
        ob_start();
        include DanceParty::CONTROLLER_DIR . "$file.php";
        $rendered_content = ob_get_contents();
        ob_clean();
        ob_end_flush();
        return $rendered_content;
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
