
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php $title ?></title>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.7/angular.min.js" charset="utf-8"></script>
        <?php wp_head(); ?>
    </head>
    <body>
        <div class="wrap" ng-app="EventCreator" ng-controller="formBuilder">
            <h1>Create a new Event</h1>

            <div ng-if="showCreateField">
                <editable-label form-value="$parent.newFieldName"></editable-label>
                <input type="text" id="field_name" name="field_name">
                <button type="button" ng-click="createField()">Add</button>
            </div>
            <button ng-if="!showCreateField" type="button" name="button" ng-click="addField()">New Field</button>
        </div>
        <?php wp_footer(); ?>
        <script src="<?php echo DanceParty::ASSET_URL . 'event_creator.js' ?>" charset="utf-8"></script>
    </body>
</html>
