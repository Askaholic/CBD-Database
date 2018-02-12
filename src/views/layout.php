<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php $title ?></title>
        <?php wp_head(); ?>
    </head>
    <body>
        <?php DanceParty::render_view( $view, $context ); ?>
        <?php wp_footer(); ?>
    </body>
</html>
