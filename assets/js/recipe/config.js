(function() {
    'use strict';
    
    var recipe = angular.module('recipe', ['ui.router']);
    
    recipe.config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
        function($stateProvider, $urlRouterProvider, $locationProvider) {
            $locationProvider.html5Mode(true).hashPrefix('!');
            
            $stateProvider.state('edit', {
                url: '/edit/{recipeId:[0-9]+}',
                abstract: true,
                controller: 'EditRecipeController',
                templateUrl: '/partials/recipe/_new.html',
                params: {
                    recipeId: { value: null, squash: true }
                }
            })
            .state('edit.description', {
                url: '',
                templateUrl: '/partials/recipe/_new-description.html'
            })
            .state('edit.ingredients', {
                url: '/ingredients',
                template: '<div ingredient-list ingredients="recipe.ingredients"></div>'
            })
            .state('edit.steps', {
                url: '/steps',
                templateUrl: '/partials/recipe/_new-steps.html' 
            });
            
            $urlRouterProvider.otherwise('/edit');
        }
    ]);
}());