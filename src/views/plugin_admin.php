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
    if( isset($context['error']) and $context['error'] != '' ) {
    ?>
        <div class="error">
            <p><?php echo $context['error'] ?></p>
        </div>
    <?php
    }
    ?>
</div>
