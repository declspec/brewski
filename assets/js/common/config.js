(function() {
    'use strict';
    
    var common = angular.module('common', []);
    
    common.config(['$httpProvider', function($httpProvider) {
        // Allow through any response that is in the expected API format.
        // also redirect the user when an unauthorized error comes through
        $httpProvider.interceptors.push(["$q", function($q) {
            return {
                responseError: function(rejection) {
                    return ('object' !== typeof(rejection.data) || !rejection.data.hasOwnProperty('success'))
                        ? $q.reject(rejection)
                        : rejection;
                }
            };
        }]);
    }]);
}());