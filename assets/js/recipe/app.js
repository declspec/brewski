angular.module('recipe').config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
    function($stateProvider, $urlRouterProvider, $locationProvider) {
        'use strict';
        
        $locationProvider.html5Mode(true).hashPrefix('!');
        
        $stateProvider
            .state('edit', {
                url: '/edit/{recipeId:[0-9]+}?base',
                abstract: true,
                controller: 'EditRecipeController',
                templateUrl: '/partials/recipe/edit.html',
                params: {
                    recipeId: { value: null, squash: true },
                    base: { value: null, squash: true }
                }
            })
            .state('edit.description', {
                url: '',
                templateUrl: '/partials/recipe/_edit-description.html'
            })
            .state('edit.ingredients', {
                url: '/ingredients',
                templateUrl: '/partials/recipe/_edit-ingredients.html'
            })
            .state('edit.steps', {
                url: '/steps',
                templateUrl: '/partials/recipe/_edit-steps.html' 
            })
            .state('edit.notes', {
                url: '/notes',
                templateUrl: '/partials/recipe/_edit-notes.html' 
            })
            .state('view', {
                url: '/{recipeId:[0-9]+}',
                controller: 'ViewRecipeController',
                templateUrl: '/partials/recipe/view.html'  
            })
        
        $urlRouterProvider.otherwise('/edit');
    }
]);