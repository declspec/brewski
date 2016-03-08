angular.module('recipe').controller('EditRecipeController', [ '$q', '$scope', '$state', '$stateParams', 'RecipeService',
    function($q, $scope, $state, $stateParams, RecipeService) {
        $scope.steps = [
            'edit.description',
            'edit.ingredients',
            'edit.steps',
            'edit.notes'
        ];  
        
        // Find the current step (you may enter the form at any one of the steps and the controller needs to initialise properly)
        $scope.step = Math.max($scope.steps.indexOf($state.current.name), 0);
        $scope.loading = true;
        $scope.errors = [];
        
        // Lookup the recipe or create a new one
        var recipePromise = $stateParams.recipeId
            ? RecipeService.find($stateParams.recipeId)
            : RecipeService.getNewRecipe();
        
        $q.when(recipePromise).then(function(recipe) {
            $scope.recipe = recipe; 
        }).finally(function() {
            $scope.loading = false;
        });
        
        // --
        // Controller Functions
        // --

        // Set up the scope functions
        $scope.next = function() {
            $scope.setStep($scope.step + 1);
        };
        
        $scope.back = function() {
            $scope.setStep($scope.step - 1);
        };
        
        $scope.setStep = function (number) {
            if (number != $scope.step && number >= 0 && number < $scope.steps.length) {
                $scope.step = number;
                $state.go($scope.steps[number]);
            }   
        };
        
        $scope.addIngredient = function(index) {
            $scope.recipe.ingredients.push({ quantity: '', description: '' }); 
        };
        
        $scope.removeIngredient = function(index) {
            if ($scope.recipe.ingredients.length > 1)
                $scope.recipe.ingredients.splice(index, 1);  
            else
                $scope.recipe.ingredients[0] = { quantity: '', description: '' };
        };
        
        $scope.addStep = function(index) {
            $scope.recipe.steps.push({ content: '' });  
        };
        
        $scope.removeStep = function(index) {
            if ($scope.recipe.steps.length > 1 )
                $scope.recipe.steps.splice(index, 1);  
            else
                $scope.recipe.steps[0] = { content: '' };
        };
        
        // Validation and submission
        $scope.submit = function() {
            if (!validate())
                return false;    
                
            RecipeService.save($scope.recipe).then(function(recipe) {
                $state.go('view', { recipeId: recipe.id });
            }, function(res) {
                $scope.errors = res.errors || [ 'An unknown error has occurred. Please try again.' ];
            });
        }
        
        function validate() {
            return true;   
        }
    }
]);