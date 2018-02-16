<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$myValue = "..";
if (isset($_POST['second_input'])) {
  $myValue = $_POST['second_input'];
}


  DanceParty::render_view_with_template( 'new_workshop.php', array('userInput' => $myValue));
 ?>
