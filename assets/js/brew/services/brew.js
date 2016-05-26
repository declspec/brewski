angular.module('brew').service('BrewService', [ '$q', '$httpq', function($q, $httpq) {
    'use strict';
    
    this.find = function(id) {
        return $q.when(null);    
    };
    
    this.createNewBrew = function(recipeId) {
        return $q.when({
           recipeId: recipeId 
        });
    };
}]);