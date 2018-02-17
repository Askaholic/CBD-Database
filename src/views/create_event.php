<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php $title ?></title>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.7/angular.min.js" charset="utf-8"></script>
        <script src="<?php echo DanceParty::ASSET_URL . 'event_creator.js' ?>" charset="utf-8"></script>
        <?php wp_head(); ?>
        <style media="screen">
            .border-light {
                border: 1px solid grey;
                border-radius: .2rem;
                padding: 1rem;
            }

            .pull-right {
                float: right;
            }
            .inline-container {
                position: relative;
            }
            .input-inline {
                position: absolute;
                right: 3px;
                top: 3px;
                bottom: 3px;
            }
            .button-sm {
                padding-left: 1em !important;
                padding-right: 1em !important;
                padding-top: .2em !important;
            }
        </style>
    </head>
    <body>
        <div class="wrap" ng-app="EventCreator" ng-controller="formBuilder">
            <h1>Create a new event</h1>
            <hr/>

            <editable tag="h3" form-value="form.name"></editable>
            <div ng-repeat="f in form.fields">
                <div class="border-light">
                    <div class="inline-container">
                        <input class="button-sm button secondary input-inline" type="button" value="Delete" ng-click="deleteField($index)">
                    </div>
                    <editable form-value="f.name"></editable>
                    <editable tag="i" form-value="f.desc"></editable>
                    <br/>
                    <input type="{{ f.type }}" id="{{ f.short_name }}" name="{{ f.short_name }}">
                </div>
                <br/>
            </div>

            <div ng-if="showCreateField">
                <br/>
                <div class="border-light">
                    <editable form-value="$parent.newFieldName"></editable>
                    <editable tag="i" form-value="$parent.newFieldDesc"></editable>
                    <br/>
                    <form-change type="$parent.newFieldType"></form-change>
                    <!-- <input type="text" id="field_name" name="field_name"> -->
                </div>
                <br/>
                <!--  -->
                <select ng-model="$parent.newFieldType">
                 <option value="text">Text Field</option>
                 <option value="checkbox">Checkbox</option>
                 <option value="radio">Radio Box</option>
                 <option value="number">number</option>
                </select>
                <!--  -->
                <button class="button secondary" type="button" ng-click="createField()">Add</button>
                <button class="button secondary" type="button" ng-click="discardField()">Cancel</button>
            </div>
            <br/>
            <form action="" method="post" ng-if="!showCreateField">
                <input type="hidden" name="event_schema" value="{{ $parent.jsonify() }}">
                <input class="button secondary" type="button" ng-click="addField()" value="New Field">
                <button class="button primary pull-right" type="submit">Submit</button>
            </form>
        </div>
        <?php wp_footer(); ?>
    </body>
</html>
