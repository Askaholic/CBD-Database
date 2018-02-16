var app = angular.module('EventCreator', []);

app.controller("formBuilder", function($scope) {
    $scope.form = {
        name: 'New Event',
        fields: []
    }

    $scope.showCreateField = false;

    $scope.addField = function() {
        $scope.showCreateField = true;
        $scope.newFieldName = "Name"
    };

    $scope.createField = function() {
        $scope.showCreateField = false;
        $scope.form.fields.push(
            {
                "name": $scope.newFieldName,
                "short_name": $scope.newFieldName.toLowerCase().replace(/ */, '_'),
                "type": "text"
            }
        );
    };

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
        console.log($attrs)
        return `
            <div>
                <` + $attrs.tag + ` for="field_name" ng-if="!$ctrl.editing" ng-dblclick="$ctrl.setEditing(true)">{{ $ctrl.formValue }}:</` + $attrs.tag + `>
                <input ng-if="$ctrl.editing" ng-model="$ctrl.formValue" ec-enter="$ctrl.setEditing(false)" type="text">
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
