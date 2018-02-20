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
        $scope.newFieldType = "text"
        $scope.newFieldDesc = "Description"
    };

    $scope.createField = function() {
        $scope.showCreateField = false;
        $scope.form.fields.push(
            {
                "name": $scope.newFieldName,
                "desc": $scope.newFieldDesc,
                "type": $scope.newFieldType
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
        return angular.toJson($scope.form);
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

app.component('formChange', {
    bindings: {
        type: '<',
    },
    controller: function() {
        if(this.items === undefined) { this.items = [] }

        this.createBox = function () {
            this.items.push();
        }
        this.discardBox = function () {
            this.items.pop();
        }

    },
    template:
    `<div ng-if="$ctrl.type === 'text'">
        <label for="uname">Description for your text filed: </label>
        <input type="text" id="uname" placeholder="eg: 'First Name: '">
    </div>
    <div ng-if="$ctrl.type === 'checkbox'">
          <li ng-repeat="ForEachButton">{{boxes}}
              <input type="checkbox" name="checkboxes">
              <label for="checkboxDescription"> Description: </label>
              <input type="text" id="checkboxDescription"><br>
          </li>
        <button class="button secondary" type="button" ng-click="$ctrl.createBox()">+</button>
        <button class="button secondary" type="button" ng-click="$ctrl.discardBox()">-</button>
    </div>`
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
