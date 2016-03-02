angular.module('recipe').service('RecipeService', [ '$q',
    function($q) {
        var defaultRecipe = {
            ingredients: [ { quantity: "", description: "" } ],
            steps: [ { content: "" } ]
        };
        
        this.newRecipe = function() {
            return angular.copy(defaultRecipe, {});  
        };
        
        this.find = function(recipeId) {
            return $q.when(null);
        };
    }
]);