<div class="wrap">
    <?php if ( isset( $error ) && $error !== '' ) { ?>
        <div class="error">
            <p><?php echo $error ?></p>
        </div>
    <?php
    }
    if ( isset( $info ) && $info !== '') { ?>
        <div class="info">
            <p><?php echo $info ?></p>
        </div>
    <?php
    }
    DanceParty::render_view( $view, $context ); ?>
</div>
