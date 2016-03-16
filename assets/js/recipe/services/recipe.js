angular.module('recipe').service('RecipeService', [ '$q', '$httpq',
    function($q, $httpq) {
        var defaultRecipe = {
            ingredients: [ { quantity: "", description: "" } ],
            steps: [ { content: "" } ]
        };
        
        this.getNewRecipe = function() {
            return angular.copy(defaultRecipe, {});  
        };
        
        this.find = function(recipeId) {
            return $httpq.get('/api/recipe/' + encodeURIComponent(recipeId)).then(function(res) {
                return res.success
                    ? res.data
                    : $q.reject(res)
            });
        };
        
        this.save = function(recipe) {
            var target = recipe.id 
                ? '/api/recipe/' + encodeURIComponent(recipe.id)
                : '/api/recipe';
                
            return $httpq.post(target, recipe).then(function(res) {
                return res.success
                    ? res.data
                    : $q.reject(res);
            });
        };
    }
]);