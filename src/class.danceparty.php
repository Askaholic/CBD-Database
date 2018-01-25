<?php

/**
 *
 */
class DanceParty
{
  public static function activation_hook() {
    include 'router.php';
    flush_rewrite_rules();
  }

  public static function deactivation_hook() {
    flush_rewrite_rules();
  }
}


?>
