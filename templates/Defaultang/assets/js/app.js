(function () {
    'use strict';
    var app = angular.module('CMSApp', ['CMSAppDirective', 'ui.router']);

    app.filter('rawHtml', ['$sce', function($sce){
	  return function(val) {
	    return $sce.trustAsHtml(val);
	  };
	}]);

	app.controller('contentListController', function($scope, $http) {
        var that = this;
		$http.get('http://localhost:8000/rest/contents').success(function(response) {
            $scope.contents = response;
        });
	});

	app.controller('contentController', function($scope, $http) {
        var that = this;
		$http.get('http://localhost:8000/rest/content/').success(function(response) {
            $scope.contents = response;
        });
	});

	app.config([
		'$stateProvider',
		'$urlRouterProvider',
		function($stateProvider, $urlRouterProvider) {
		  $urlRouterProvider.otherwise("/home");

		  $stateProvider
		    .state('home', {
		      url: '/home',
		      templateUrl: 'views/home.html',
		      controller: 'contentListController'
		    })
		    .state('post', {
		    	url: '/post/{alias}',
		    	templateUrl: 'views/post.html',
		    	controller: 'contentController'
		    })
		    ;

		  $urlRouterProvider.otherwise('home');
	}]);

	
})();