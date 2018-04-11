var app = angular.module('EventCreator', []);

function makeShortName(name) {
    return name.toLowerCase().replace(/[^a-zA-Z0-9_]/, "").replace(/ +/, "_");
}

app.controller("formBuilder", function($scope, $http) {
    $scope.form = {
        name: 'Name your Event',
        fields: []
    }

    //add template form to fields
    //$scope.form.fields.push({});
    //editable event description
    //TODO better entry field for eventDescription
    //TODO find out how to pass field variable to formBuilder calls in create_event.php
    $scope.form.fields.push({'name':'eventdesc', 'type':'eventdesc', 'desc': 'Describe your Event', 'nodelete':true, 'items':[]});
    //user info and required(not deletable) template demo
    $scope.form.fields.push({'name':'userinfo', 'type':'userinfo', 'desc': 'Sign up for Event', 'nodelete':true, 'required':true, 'items':[]});
    //TODO make default pay like userinfo
    //TODO make addable types like allergies, lodging
    $scope.form.fields.push({'name':'childinfo', 'type':'childinfo', 'desc': 'Number of people attending', 'nodelete':false, 'required':true, 'items':[]});

    $scope.showCreateField = false;

    $scope.addField = function() {
        $scope.showCreateField = true;
        $scope.newFieldName = "Name"
        $scope.newFieldType = "text"
        $scope.newFieldDesc = "Description"
        $scope.newField = {};
    };

    $scope.createField = function() {
        $scope.showCreateField = false;
        $scope.newField['type'] = $scope.newFieldType;
        $scope.newField['short_name'] = makeShortName($scope.newField['name']);
        $scope.form.fields.push(
            $scope.newField
        );
        console.log($scope.newField);
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
        if ($attrs.tag === undefined) { $attrs.tag = "label" }

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

app.component('defaultForm', {
    bindings: {
        output: '=',

    },
    controller: function() {
        this.$onInit = () => {

            this.output = {
                "items": []
            }
            this.eachElement = {
                "firstName": "First Name",
                "lastName": "Last Name",
                "age": 0
            }
        }

        this.createBox = () => {
            this.output.items.push(this.eachElement);
        }
        this.discardBox = () => {
            if(this.numItems !== 0) {
                this.output.items.pop();
            }
        }
    },
    template:
   `
    <h5>Number of Members:</h5>

    <div ng-repeat="i in $ctrl.output.items track by $index">
        <editable form-value="$ctrl.output.items[$index].firstName"></editable>
        <editable form-value="$ctrl.output.items[$index].lastName"></editable>
        <label for="UserAge">Age:</label>
        <input type="number" id="UserAge" ng-model="$ctrl.output.items[$index].age" min="0" max="200">
    </div>
    <button class="button secondary" type="button" ng-click="$ctrl.createBox()">+</button>
    <button class="button secondary" type="button" ng-click="$ctrl.discardBox()">-</button>

    `
});

app.component('formChange', {
    bindings: {
        type: '<',
        output: '=',

    },
    controller: function() {
        this.$onInit = () => {
            this.textString = "Label";

            this.output = {
                "name": "Name",
                "desc": "Description",
                "required": true,
                "items": []
            }
        }

        this.createBox = () => {
            this.output.items.push(this.textString);
        }
        this.discardBox = () => {
            if(this.numItems !== 0) {
                this.output.items.pop();
            }
        }

    },
    template:
   `
   <div class="pull-right">
    <label for="checkbox1">Required to Answer:</label>
     <label class="switch">
         <input type="checkbox" id="checkbox1" ng-model="$ctrl.output.required" checked>
         <span class="slider round"></span>
     </label>
   </div>
    <editable form-value="$ctrl.output.name"></editable>
    <editable tag="i" form-value="$ctrl.output.desc" nullable="true"></editable>
    <div ng-if="$ctrl.type === 'checkbox' || $ctrl.type === 'radio'">
        <div ng-repeat="i in $ctrl.output.items track by $index">
            <input type="{{ $ctrl.type }}" name="{{ $ctrl.output.name }}"/>
            <editable form-value="$ctrl.output.items[$index]"></editable>
        </div>
        <button class="button secondary" type="button" ng-click="$ctrl.createBox()">+</button>
        <button class="button secondary" type="button" ng-click="$ctrl.discardBox()">-</button>
    </div>
    <div ng-if="$ctrl.type === 'textarea'">
        <textarea></textarea>
    </div>
    <div ng-if="!($ctrl.type === 'checkbox' || $ctrl.type === 'radio' || $ctrl.type === 'textarea')">
        <input type="{{ $ctrl.type }}"/>
    </div>
    `
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
