<form method="post" action="">
<?php
wp_nonce_field('submit', 'event_nonce');
DanceParty::render_view('event_snippet.php',
    array(
        'event' => $event,
        'user' => $user
    )
);
?>
<input type="submit" name="submit" value="Register for Event">
</form>
