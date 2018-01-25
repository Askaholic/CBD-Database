<?php

route( 'signup$', 'signup.php' );

add_filter( 'query_vars', 'dp_router_query_vars' );
function dp_router_query_vars( $query_vars )
{
    $query_vars[] = 'dp_router_page';
    return $query_vars;
}

add_action( 'parse_request', 'dp_router_parse_request' );
function dp_router_parse_request( &$wp )
{
    if ( array_key_exists( 'dp_router_page', $wp->query_vars ) ) {
        echo $wp->query_vars['db_router_page'];
        exit();
    }
    return;
}

function route( $regex, $target ) {
  add_rewrite_rule( $regex, 'index.php?dp_router_page=' . $target, 'top' );
}

?>
