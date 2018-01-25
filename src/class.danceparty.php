<?php

/**
 *
 */
class DanceParty
{
  public static function activation_hook() {
    require_once( DP_PLUGIN_DIR . 'router.php' );
    flush_rewrite_rules();
  }

  public static function deactivation_hook() {
    flush_rewrite_rules();
  }
}


?>
