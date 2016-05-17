(function() {	
	'use strict';
    
    var app = angular.module('CMSAppDirective', []);

    app.directive('content', function() {
    	return {
    		templateUrl: function(elem, attr) {
    			return 'directives/content-'+attr.type+'.html'
    		}
    	}
    })

})();