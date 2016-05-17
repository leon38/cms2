(function(){
    var app = angular.module('CMSApp', ['ngRoute', 'CMSAppControllers', 'CMSAppServices', 'CMSAppDirective']);

    app.config(['$routeProvider',
        function ($routeProvider) {
            $routeProvider.
            when('/', {
                templateUrl: 'views/home.html',
            }).
            when('/content/:contentId', {
                templateUrl: 'views/content.html',
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

})();