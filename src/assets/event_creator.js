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
        output: '=',

    },
    controller: function() {
      console.log("Created controller");
      // Text Based:
        if(this.textString === undefined) { this.textString = "(Click to edit description)" }
      // Checkbox or Radio Based:
        if(this.items === undefined) { this.items = [] }

        if(this.type === 'text'){
            this.output = this.textString;
        }
        else if (this.type === 'checkbox' || this.type === 'radio') {
            this.output = this.items;
        }

        this.createBox = function () {
            this.items.push(this.textString);
            //console.log(this.items);
        }
        this.discardBox = function () {
            if(this.numItems !== 0) {
                this.items.pop();
            }
        }

    },
    template:
    // <input type="text" id="uname" placeholder="eg: 'First Name'">
    // <input type="checkbox" name="checkboxes">
    // (On other side, something like) <label for="TextEditableID"> (Var) </label>
   `<div ng-if="$ctrl.type === 'text' || $ctrl.type === 'number'">
        Description:
        <editable form-value="$ctrl.textString"></editable>
    </div>
    <div ng-if="$ctrl.type === 'checkbox' || $ctrl.type === 'radio'">
          <li ng-repeat="i in $ctrl.items track by $index">
              <label for="checkboxDescription"> Description: </label>
              <editable form-value="$ctrl.items[$index]" id="checkboxDescription"></editable>
              <br>
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
