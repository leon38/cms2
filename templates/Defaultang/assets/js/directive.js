(function() {
	'use strict';

    var app = angular.module('CMSAppDirective', []);

    app.directive('contentList', function() {
    	return {
    		restrict: 'E',
    		templateUrl: function(elem, attr) {
    			return 'directives/content-list-'+attr.type+'.html'
    		}
    	}
    });

    app.directive('content', function() {
    	return {
    		restrict: 'E',
    		scope: {
    			content: '='
    		},
    		templateUrl: function(elem, attr) {
    			return 'directives/content-'+attr.type+'.html'
    		}
    	}
    })

})();