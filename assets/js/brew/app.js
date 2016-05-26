angular.module('brew').config(['$stateProvider', '$urlRouterProvider', '$locationProvider', '$modalDialogProvider',
        function($stateProvider, $urlRouterProvider, $locationProvider, $modalDialogProvider) {
            $locationProvider.html5Mode(true).hashPrefix('!');
            
            $stateProvider
                .state('create', {
                    url: '/create/{brewId:[0-9]+}?base',
                    controller: 'CreateBrewController',
                    templateUrl: '/partials/brew/create.html',
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
                
            $urlRouterProvider.otherwise('/create');
        }
    ]);