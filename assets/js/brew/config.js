(function() {
    'use strict';
    
    var recipe = angular.module('brew', ['ui.router','common','recipe']);
    
    recipe.config(['$stateProvider', '$urlRouterProvider', '$locationProvider', '$modalDialogProvider',
        function($stateProvider, $urlRouterProvider, $locationProvider, $modalDialogProvider) {
            $locationProvider.html5Mode(true).hashPrefix('!');
            
            $stateProvider
                .state('edit', {
                    url: '/edit/{brewId:[0-9]+}?base',
                    //abstract: true,
                    controller: 'EditBrewController',
                    templateUrl: '/partials/brew/edit.html',
                    params: {
                        brewId: { value: null, squash: true },
                        base: { value: null, squash: true }
                    }
                });
            
            $modalDialogProvider.register('ingredients-dialog', {
                cancellable: true,
                template: '<div class="ingredients-dialog"><h1>Ingredients</h1><ul><li ng-repeat="ingredient in ingredients">HELLO</li></ul></div>',
                controller: [ '$scope', '$modalDialogParams', function($scope, $modalDialogParams) {
                    $scope.ingredients = $modalDialogParams.ingredients;  
                }] 
            });
                
            $urlRouterProvider.otherwise('/edit');
        }
    ]);
}());