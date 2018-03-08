<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="<?php echo DanceParty::ASSET_URL . 'fallback.css' ?>">
        <title><?php echo $title; ?></title>
        <?php wp_head(); ?>
    </head>

    <body>
        <div class="wrap">
            <?php DanceParty::render_view( $view, $context ); ?>
        </div>
        <?php wp_footer(); ?>
    </body>
</html>
