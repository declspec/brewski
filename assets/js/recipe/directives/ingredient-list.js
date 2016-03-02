angular.module('recipe').directive('ingredientList', function() {
    return {
        restrict: 'A',
        scope: { ingredients: '=' },
        templateUrl: '/partials/recipe/_ingredients-list.html',
        link: function(scope, el, attrs) {
            scope.add = function(index) {
                var newIngredient = { quantity: "", description: "" };
                
                if (index >= scope.ingredients.length)
                    scope.ingredients.push(newIngredient);
                else
                    scope.ingredients.splice(index, 0, newIngredient);
            };
            
            scope.remove = function(index) {
                if (index >= 0 && index < scope.ingredients.length)
                    scope.ingredients.splice(index, 1);  
            };
        }   
    } 
});