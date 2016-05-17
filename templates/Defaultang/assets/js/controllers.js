(function () {
    'use strict';
    var app = angular.module('CMSAppControllers', ['CMSAppDirective']);

    app.filter('rawHtml', ['$sce', function($sce){
	  return function(val) {
	    return $sce.trustAsHtml(val);
	  };
	}]);

	app.controller('contentListController', ['Travel', function(Travel) {
        this.contents = Travel.query();
	}]);

	app.controller('contentController', ['Travel', '$routeParams', '$rootScope', function(Travel, $routeParams, $rootScope) {
		var c = this;
        this.content = Travel.get({contentId: $routeParams.contentId}, function (content) {
            c.content = content;
            $rootScope.classHeader = 'transparent';
        });
	}]);

})();