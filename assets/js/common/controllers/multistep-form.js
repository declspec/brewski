angular.module('common').controller('MultiStepFormController', [ '$scope', '$state',
    function($scope, $state) {
        'use strict';
        
        if ('undefined' === typeof($scope.steps)) 
            throw new TypeError('steps must be configured by child controller before constructing controller');
        
        $scope.errors = [];
        $scope.currentStep = Math.max(findStepIndexByName($state.current.name), 0);
            
        // Step-related functions
        $scope.next = function() {
            $scope.setStep($scope.currentStep + 1);   
        };
        
        $scope.back = function() {
            $scope.setStep($scope.currentStep - 1);  
        };
        
        $scope.setStep = function(stepNo) {
            if (stepNo !== $scope.currentStep && stepNo >= 0 && stepNo < $scope.steps.length) {
                $scope.currentStep = stepNo;
                $state.go($scope.steps[stepNo].state);   
            }
        };

        // protected helper functions
        this.applyError = function(field, message, redirect) {
            $scope.errors.push(message);
            var stepIdx = findStepIndexByField(field);
            
            if (stepIdx >= 0 && redirect) {
                $scope.setStep(stepIdx);   
                return true;
            }
            
            return false;
        };
        
        this.applyErrors = function(errors) {
            if ('object' !== typeof(errors))
                $scope.errors.push(errors);
            else if (Array.isArray(errors))
                $scope.errors = $scope.errors.concat(errors);
            else {
                var redirect = true;
                for(var field in errors) {
                    if (errors.hasOwnProperty(field) && this.applyError(field, errors[field], redirect))
                        redirect = false;
                }   
            }  
        };
        
        this.resetErrors = function() {
            $scope.errors = [];   
        };
        
        // private helper functions
        function findStepIndexByName(name) {
            return findStepIndex(function(s) {
                return s.state === name;
            });
        }
        
        function findStepIndexByField(name) {
            return findStepIndex(function(s) {
                return s.fields.indexOf(name) >= 0;
            });
        }
        
        function findStepIndex(cb) {
            for(var i = 0, j = $scope.steps.length; i < j; ++i) {
                if (cb($scope.steps[i]))
                    return i;
            }
            return -1;   
        }
    }
]);