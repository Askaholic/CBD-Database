var app = angular.module('EventCreator', []);

app.controller("formBuilder", function($scope, $http) {
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

    $scope.jsonify = function() {
        return JSON.stringify($scope.form);
    }
});

app.component('editable', {
    bindings: {
        formValue: '=',
        tag: '<',
        nullable: '<'
    },
    controller: function() {
        this.setEditing = function (value) {
            if (value) {
                this.default = this.formValue;
            }
            this.editing = value;
            if (this.default !== undefined && this.formValue === "" && !this.nullable) {
                this.formValue = this.default;
            }
        }
    },
    template: function ($element, $attrs) {
        if ($attrs.tag == undefined) { $attrs.tag = "label" }

        return `
            <div>
                <` + $attrs.tag + ` title="Double click to edit" for="field_name" ng-if="!$ctrl.editing" ng-dblclick="$ctrl.setEditing(true)">{{ $ctrl.formValue }}
                </` + $attrs.tag + `>
                <div style="position: relative;">
                    <input ng-if="$ctrl.editing" ng-model="$ctrl.formValue" ec-enter="$ctrl.setEditing(false)" type="text" ec-init-selected>
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

/* Directive for setting text to be selected on element load */
app.directive('ecInitSelected', function() {
    return {
        restrict: 'A',
        link: function($scope, element, attrs) {
            var isLoaded = false;
            $scope.$watch(attrs.value, function(val) {
                if (!isLoaded && val != "") {
                    element[0].setSelectionRange(0, element[0].value.length);
                    element[0].focus();
                }
            });
        }
    }
});
