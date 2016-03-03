angular.module('recipe').controller('ViewRecipeController', [ '$scope', '$stateParams', 'RecipeService',
    function($scope, $stateParams, RecipeService) {
        $scope.loading = true;
        
        RecipeService.find($stateParams.recipeId).then(function(recipe) {
            $scope.recipe = recipe;
        }).finally(function() {
            $scope.loading = false;
        });
    }
]);