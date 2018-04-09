<?php

//debug
// echo "event found! <br>";
// $s = count( $events );
// $json = $events[0]->schema_info;
// echo "$json <br><br>";
?>
<form method="post" action="">
<?php
wp_nonce_field('submit', 'event_nonce');
DanceParty::render_view('event_snippet.php',
    array(
        'event' => $event
    )
);
?>
<!-- Should button say Join Event or something less generic than Submit? -->
<input type="submit" name="submit" value="Submit">
</form>
<?php

 ?>
