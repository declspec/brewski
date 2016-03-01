(function() {
    'use strict';
    
    var recipe = angular.module('recipe', ['ui.router']);
    
    recipe.config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
        function($stateProvider, $urlRouterProvider, $locationProvider) {
            $locationProvider.html5Mode(true).hashPrefix('!');
            
            $stateProvider.state('new', {
                url: '/new',
                abstract: true,
                controller: 'NewRecipeController',
                templateUrl: '/partials/recipe/_new.html'   
            })
            .state('new.description', {
                url: '',
                templateUrl: '/partials/recipe/_new-description.html'
            })
            .state('new.ingredients', {
                templateUrl: '/partials/recipe/_new-ingredients.html' 
            })
            .state('new.steps', {
                templateUrl: '/partials/recipe/_new-steps.html' 
            });
            
            $urlRouterProvider.otherwise('/new');
        }
    ]);
}());