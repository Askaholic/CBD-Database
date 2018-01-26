<?php

route( 'test/?$', 'test.php' );

function route( $regex, $target ) {
    add_rewrite_rule( $regex, 'index.php?dp_router_page=1' . $target, 'top' );
}

?>
