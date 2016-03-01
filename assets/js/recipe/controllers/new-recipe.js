angular.module('recipe').controller('NewRecipeController', [ '$scope', '$state',
    function($scope, $state) {
        $scope.step = 0;
        
        $scope.recipe = {
            ingredients: [
                { description: "" }
            ]
        };

        $scope.steps = [
            'description',
            'ingredients',
            'steps'
        ];  
        
        $scope.next = function() {
            setStep($scope.step + 1);
        };
        
        $scope.back = function() {
            setStep($scope.step - 1);
        };
        
        function setStep(number) {
            if (number != $scope.step && number >= 0 && number < $scope.steps.length) {
                $scope.step = number;
                $state.go('new.' + $scope.steps[number]);
            }   
        }
    }
]);