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
        $post_id = wp_insert_post( array(
            'post_title' => $name,
            'post_content' => '',
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_name' => $endpoint,
            'guid' => "http://localhost/$endpoint",
            'post_type' => 'page'
        ));

        if ( $post_id === 0 ) {
            throw new Exception("Error creating page '$endpoint'");
        }
    }

    static function init_hooks() {
        add_filter( 'query_vars', array( 'Router', 'query_vars' ) );
        add_action( 'parse_request', array( 'Router', 'parse_request' ) );
        // add_filter( 'the_content', array( 'Router', 'insert_php' ) , 9 );
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

    static function insert_php($content) {
        $content_copy = $content;
		preg_match_all('!<[?]php(.*?)[?]>!s',$content_copy, $matches);

		$nummatches = count($matches[0]);
		for( $i = 0; $i < $nummatches; $i++ ) {
			ob_start();
			eval( $matches[1][$i] );
			$replacement = ob_get_contents();
			ob_clean();
			ob_end_flush();
			$content_copy = preg_replace('/'.preg_quote($matches[0][$i],'/').'/', $replacement, $content_copy, 1);
		}
		return $content_copy;
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
