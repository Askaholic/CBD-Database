<?php

function writeToFooter()
{
  //echo "<p>Hi footer</p>";
}
function writeToHeader()
{
//  echo "<p>Hi header</p>";
}
function writeToMenu()
{
  echo '<a href="signup">Sign Up</a>';
}
function writeToIframeMenu()
{
  //I'm pretty sure there's a better way of doing this
  echo '<form style="float: left" method="post" action="test">';
  echo '<input type="hidden" name="menu" value="index.php/home">';
  echo '<input type="submit" value="Home"></form>';

  echo '<form style="float: left" method="post" action="test">';
  echo '<input type="hidden" name="menu" value="signup">';
  echo '<input type="submit" value="Sign Up"></form>';
}

//adding template hook actions
//just example actions for now
add_action('wp_footer', 'writeToFooter');
add_action('wp_head', 'writeToHeader');
add_action('wp_meta', 'writeToMenu');
//custom hook
add_action('wp_iframe', 'writeToIframeMenu');

class theme
{
//build once theme decisions are made
}

?>
