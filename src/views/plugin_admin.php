<div class="wrap">
    <h1><?php echo DanceParty::NAME ?> Options</h1>

    <form action="options.php" method="post">
        <?php

            settings_fields( DanceParty::OPTION_GROUP );
            do_settings_sections( DanceParty::OPTION_GROUP );

            submit_button();
        ?>
    </form>
    <?php
    if( isset($error) and $error != '' ) {
    ?>
        <div class="error">
            <p><?php echo $error ?></p>
        </div>
    <?php
    }

    if( isset($info) and $info != '' ) {
    ?>
        <div class="updated notice">
            <p><?php echo $info ?></p>
        </div>
    <?php
    }
    ?>
</div>
