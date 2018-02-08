<div class="wrap">
    <h1><?php echo DanceParty::NAME ?> Options</h1>

    <form method="post">
        <?php

            wp_nonce_field( 'dp-options-update' );
            settings_fields( DanceParty::OPTION_GROUP );
            do_settings_sections( DanceParty::OPTION_GROUP );

            submit_button();
        ?>
    </form>

    <?php

        FormBuilder::options_form('POST', '',
            FormBuilder::input_f('text', 'db_host', 'Host')
        );
    ?>
</div>
