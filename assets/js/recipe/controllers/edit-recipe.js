angular.module('recipe').controller('EditRecipeController', [ '$q', '$scope', '$state', '$stateParams', 'RecipeService',
    function($q, $scope, $state, $stateParams, RecipeService) {
        $scope.steps = [
            'edit.description',
            'edit.ingredients',
            'edit.steps'
        ];  
        
        // Find the current step (you may enter the form at any one of the steps and the controller needs to initialise properly)
        $scope.step = Math.max($scope.steps.indexOf($state.current.name), 0);

        // Set up the scope functions
        $scope.next = function() {
            setStep($scope.step + 1);
        };
        
        $scope.back = function() {
            setStep($scope.step - 1);
        };
        
        $scope.addIngredient = function(index) {
            $scope.ingredients.splice(index, 0, { quantity: '', description: '' }); 
        };
        
        $scope.removeIngredient = function(index) {
            $scope.ingredients.splice(index, 1);  
        };
        
        $scope.addStep = function(index) {
            $scope.steps.splice(index, 0, { content: '' });  
        };
        
        $scope.removeStep = function(index) {
            $scope.steps.splice(index, 1);  
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
                $state.go($scope.steps[number]);
            }   
        }
    }
]);