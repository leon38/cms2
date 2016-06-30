var travelsService = angular.module('CMSAppServices', ['ngResource']);

travelsService.factory('Travel', ['$resource', function($resource) {
	return $resource('http://localhost:8000/rest/:contentId.json', {}, {
		query: {method: 'GET', params: {contentId: 'contents'}, isArray: true}
	});
}]);