<?php
foreach ($events as $event) {
?>
<br/>
<div class="event-preview">
    <h3><?php echo htmlspecialchars( $event->name ); ?></h3>
    <a href="#">Preview</a>

    <form action="" method="post">
    <?php
        wp_nonce_field('submit', 'event_review_nonce');
    ?>
    <input type="hidden" name="id" value="<?php echo $event->id ?>">
    <input type="hidden" name="enabled" value="<?php echo $event->enabled === true ? "true" : "false"; ?>">
    <input type="submit" value="<?php echo $event->enabled === true ? "Disable" : "Enable"; ?>">
    </form>
</div>
<hr/>
<?php
}
?>
