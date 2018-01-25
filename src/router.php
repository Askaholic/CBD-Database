<?php

route( 'test/?$', 'signup.php' );

add_filter( 'query_vars', 'dp_router_query_vars' );
function dp_router_query_vars( $query_vars )
{
    error_log("Adding query var");
    $query_vars[] = 'dp_router_page';
    return $query_vars;
}

add_action( 'parse_request', 'dp_router_parse_request' );
function dp_router_parse_request( &$wp ) {
  error_log("ahfsdlkjhdflkasjhdflkasjdhflaksdjhf");
    if ( array_key_exists( 'dp_router_page', $wp->query_vars ) ) {
        include 'controllers/signup.php';
        exit();
    }
    return;
}

function route( $regex, $target ) {
    add_rewrite_rule( $regex, 'index.php?dp_router_page=1' . $target, 'top' );
}

?>
