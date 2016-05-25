angular.module('brew').controller('EditBrewController', [ '$q', '$scope', '$state', '$stateParams', '$modalDialog', '$controller', 'BrewService', 'RecipeService',
    function($q, $scope, $state, $stateParams, $modalDialog, $controller, BrewService, RecipeService) {
        $scope.loading = true;
        
        // Initialize the parent controller
        var self = angular.extend(this, $controller('MultiStepFormController', { $scope: $scope }));
        
        // Lookup/create the brew
        var brewPromise = $stateParams.brewId
            ? BrewService.find($stateParams.brewId)
            : BrewService.createNewBrew($stateParams.recipe);
            
        $q.when(brewPromise).then(function(brew) {
            $scope.brew = brew;
            return RecipeService.find(brew.recipeId);
        }).then(function(recipe) {
            $scope.recipe = recipe; 
        }).finally(function() {
            $scope.loading = false; 
        });
        
        // --
        // Controller functions
        // --
        $scope.showIngredients = function() {
            $modalDialog.show('ingredients-dialog', { ingredients: $scope.recipe.ingredients });
        };
    }
]);