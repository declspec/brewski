angular.module('recipe').controller('EditRecipeController', [ '$q', '$scope', '$state', '$stateParams', 'RecipeService',
    function($q, $scope, $state, $stateParams, RecipeService) {
        $scope.steps = [
            { state: 'edit.description', fields: [ 'name', 'description' ] },
            { state: 'edit.ingredients', fields: [ 'ingredients' ] },
            { state: 'edit.steps', fields: [ 'steps' ] },
            { state: 'edit.notes', fields: [ 'estimatedTime', 'notes' ] }
        ];  
        
        // Find the current step (you may enter the form at any one of the steps and the controller needs to initialise properly)
        $scope.step = Math.max(findStepIndexByName($state.current.name), 0);
        $scope.loading = true;
        $scope.errors = [];
        
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

        // Set up the scope functions
        $scope.next = function() {
            $scope.setStep($scope.step + 1);
        };
        
        $scope.back = function() {
            $scope.setStep($scope.step - 1);
        };
        
        $scope.setStep = function (num) {
            if (num != $scope.step && num >= 0 && num < $scope.steps.length) {
                $scope.step = num;
                $state.go($scope.steps[num].state);
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
                // Handle the error case
                if (!res || !res.errors)
                    $scope.errors = [ 'An unknown error occurred when submitting your recipe, please try again.' ];
                else if (Array.isArray(res.errors))
                    $scope.errors = res.errors;
                else {
                    $scope.errors = [];
                    for(var key in res.errors) {
                        if (res.errors.hasOwnProperty(key)) {
                            $scope.errors.push(res.errors[key]);
                            var step = findStepIndexByField(key);
                            if (step >= 0)
                                $scope.setStep(step);
                        }                        
                    }
                }
            });
        }
        
        function validate() {
            return true;
        }
        
        function findStepIndexByName(name) {
            return findStepIndex(function(s) {
                return s.state === name;
            });
        }
        
        function findStepIndexByField(name) {
            return findStepIndex(function(s) {
                return s.fields.indexOf(name) >= 0;
            });
        }
        
        function findStepIndex(cb) {
            for(var i = 0, j = $scope.steps.length; i < j; ++i) {
                if (cb($scope.steps[i]))
                    return i;
            }
            
            return -1;   
        }
    }
]);