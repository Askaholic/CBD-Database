var app = angular.module('EventCreator', []);

app.controller("formBuilder", function($scope) {
    $scope.form = {
        name: 'Untitled Event',
        fields: []
    }

    $scope.showCreateField = false;

    $scope.addField = function() {
        $scope.showCreateField = true;
        $scope.newFieldName = "Name"
        $scope.newFieldDesc = "Description"
    };

    $scope.createField = function() {
        $scope.showCreateField = false;
        $scope.form.fields.push(
            {
                "name": $scope.newFieldName,
                "short_name": $scope.newFieldName.toLowerCase().replace(/ */, '_'),
                "desc": $scope.newFieldDesc,
                "type": "text"
            }
        );
    };

    $scope.deleteField = function(index) {
        $scope.form.fields.splice(index, 1);
    }

    $scope.discardField = function() {
        $scope.showCreateField = false;
    };

});

app.component('editable', {
    bindings: {
        formValue: '=',
        tag: '<'
    },
    controller: function() {
        this.setEditing = function (value) {
            this.editing = value;
        }
    },
    template: function ($element, $attrs) {
        if ($attrs.tag == undefined) { $attrs.tag = "label" }

        return `
            <div>
                <` + $attrs.tag + ` title="Double click to edit" for="field_name" ng-if="!$ctrl.editing" ng-dblclick="$ctrl.setEditing(true)">{{ $ctrl.formValue }}
                </` + $attrs.tag + `>
                <div style="position: relative;">
                    <input ng-if="$ctrl.editing"ng-model="$ctrl.formValue" ec-enter="$ctrl.setEditing(false)" type="text">
                    <input ng-if="$ctrl.editing" class="button input-inline" value="Done" type="button" ng-click="$ctrl.setEditing(false)">
                </div>
            </div>
        `
    }
});

/* Directive for when the enter key is pressed */
app.directive('ecEnter', function() {
    return function($scope, element, attrs) {
        element.bind('keydown keypress', function(event) {
            if(event.which === 13) {
                $scope.$apply(function() {
                    $scope.$eval(attrs.ecEnter);
                });
            }
        });
    };
});
