angular.module('brew').controller('CreateBrewController', [ '$q', '$scope', '$state', '$stateParams', '$modalDialog', 'BrewService', 'RecipeService',
    function($q, $scope, $state, $stateParams, $modalDialog, BrewService, RecipeService) {
        $scope.loading = true;
        
        // Lookup/create the brew
        var brewPromise = $stateParams.brewId
            ? BrewService.find($stateParams.brewId)
            : BrewService.createNewBrew($stateParams.recipe);
            
        $q.when(brewPromise).then(function(brew) {
            $scope.brew = brew;
            return brew ? RecipeService.find(brew.recipeId) : null; 
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