<h1>Available</h1>
<form action="" method="post">
    <input type="hidden" name="action_type" value="schedule" id='type-input'>
    <?php
    wp_nonce_field('submit', 'event_review_nonce');
    ?>
    <select name="event_id">
<?php
foreach ($events as $event) {
?>
<option value="<?php echo $event->id ?>"><?php echo htmlspecialchars( $event->name ); ?></option>

<?php
}
?>
    </select>

    <input type="submit" value="Preview" onclick="setType('preview')">
<?php
FormBuilder::input('date', 'start_date', 'Start Date');
FormBuilder::input('date', 'end_date', 'End Date');
?>
<input type="submit" value="Schedule" onclick="setType('schedule')">

</form>
