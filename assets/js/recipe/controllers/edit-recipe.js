angular.module('recipe').controller('EditRecipeController', [ '$q', '$scope', '$state', '$stateParams', '$controller', 'RecipeService',
    function($q, $scope, $state, $stateParams, $controller, RecipeService) {
        $scope.loading = true;
        
        $scope.steps = [
            { state: 'edit.description', fields: [ 'name', 'description' ] },
            { state: 'edit.ingredients', fields: [ 'ingredients' ] },
            { state: 'edit.steps', fields: [ 'steps' ] },
            { state: 'edit.notes', fields: [ 'estimatedTime', 'notes' ] }
        ];  
        
        // Initialize the parent controller
        var self = angular.extend(this, $controller('MultiStepFormController', { $scope: $scope }));

        // Lookup the recipe or create a new one
        var recipePromise = $stateParams.recipeId || $stateParams.base
            ? RecipeService.find($stateParams.recipeId || $stateParams.base)
            : RecipeService.getNewRecipe();
        
        $q.when(recipePromise).then(function(recipe) {
            $scope.recipe = recipe; 
            
            // If providing a 'parent' recipe, swap the id/parentId fields.
            if ($stateParams.base && recipe) {
                recipe.parentId = recipe.id;
                recipe.id = null;
            }
        }).finally(function() {
            $scope.loading = false;
        });
        
        // --
        // Controller Functions
        // --
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
                var errors = (res && res.errors)
                    || 'An unknown error occurred when submitting your recipe, please try again.';
                    
                self.resetErrors();
                self.applyErrors(errors);
            });
        }
        
        function validate() {
            return true;
        }
    }
]);