angular.module('recipe').controller('EditRecipeController', [ '$q', '$scope', '$state', '$stateParams', 'RecipeService',
    function($q, $scope, $state, $stateParams, RecipeService) {
        $scope.step = 0;
       
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
        
        // Lookup the recipe or create a new one
        var recipePromise = $stateParams.recipeId
            ? RecipeService.find($stateParams.recipeId)
            : RecipeService.newRecipe();
        
        $q.when(recipePromise).then(function(recipe) {
            $scope.recipe = recipe; 
        });
        
        function setStep(number) {
            if (number != $scope.step && number >= 0 && number < $scope.steps.length) {
                $scope.step = number;
                $state.go('edit.' + $scope.steps[number]);
            }   
        }
    }
]);