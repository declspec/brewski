angular.module('common').directive('autosizeTextarea', function() {
    'use strict';
    
    return {
        restrict: 'A',
        link: function(scope, $el, attrs) {
            var lastHeight = NaN,
                el = $el[0];

            $el.on('keyup', resize);
            resize.call(el);
            
            function resize() {
                if (this.scrollHeight != lastHeight) {
                    lastHeight = this.scrollHeight;   
                    this.style.height = '1px';
                    this.style.height = this.scrollHeight + 'px';
                }
            }
            
            function delayedResize() {}
        }  
    };
});