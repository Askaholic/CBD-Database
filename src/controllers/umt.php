<?php

require_once( DP_PLUGIN_DIR . 'models/user.php' );
require_once( DP_PLUGIN_DIR . 'models/event.php' );
require_once( DP_PLUGIN_DIR . 'class.passwordhash.php' );

$schema = json_decode(<<<end
{
    "columns" : [
        {
            "name": "first_name",
            "type": "text",
            "constraints": "not null"
        },
        {
            "name": "lodging",
            "type": "multivalued",
            "constraints": "not null default='tent'",
            "options" : [
                "tent",
                "cabin_male",
                "cabin_female"
            ]
        }
    ]
}
end
);

$schema2 = json_decode(<<<end
{"columns": [{"name": "lodging", "type": "multivalued", "constraints": "", "description": "Choose your lodging option"}]}
end
);

// echo json_last_error_msg();
// var_dump($schema);

echo json_schema_to_column_string( $schema2 );
?>

<h1>User model test</h1>
