
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

  // if (isset($_POST['firstForm'])) {
  //   $myValue = $_POST['firstForm'];
  // }

if (isset($_POST['second_input'])) {
  $myValue = $_POST['second_input'];
}


  DanceParty::render_view_with_template( 'Cameron_test.php', array('something' => $myValue));
 ?>
