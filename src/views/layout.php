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
            <?php if ( isset( $error ) ) { ?>
                <div class="error">
                    <p><?php echo $error ?></p>
                </div>
            <?php
            } 
            if ( isset( $info ) ) { ?>
                <div class="info">
                    <p><?php echo $info ?></p>
                </div>
            <?php
            }
            DanceParty::render_view( $view, $context ); ?>
        </div>
        <?php wp_footer(); ?>
    </body>
</html>
