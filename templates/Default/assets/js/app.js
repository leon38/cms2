(function () {
    'use strict';
    var app = angular.module('CMSApp', []);

	// app.controller('phoneController', function() {
	// 	this.phone = nexus_s;
	// 	this.mainImageUrl = this.phone.images[0];
	// 	this.email = "test@test.com"
	// 	this.setImage = function(setImg) {
	// 		this.mainImageUrl = setImg;
	// 	};
	// 	this.postPhone = function(phone) {
	// 		alert(this.email);
	// 	}
	// });

    var contents = {"contents":[{"id":1,"title":"Test article","description":"<p>Vestibulum fringilla pede sit amet augue. Curabitur blandit mollis lacus. Morbi ac felis. Phasellus volutpat, metus eget egestas mollis, lacus lacus blandit dui, id egestas quam mauris ut lacus. Pellentesque libero tortor, tincidunt et, tincidunt eget, semper nec, quam.<\/p><p>Duis vel nibh at velit scelerisque suscipit. Morbi mollis tellus ac sapien. Curabitur at lacus ac velit ornare lobortis. Quisque malesuada placerat nisl. Nullam tincidunt adipiscing enim.<\/p>","language":{"id":1,"name":"Fran\u00e7ais","code_local":"fr_fr","code_lang":"fr_fr","sens_ecriture":"ltr","ordre":0,"default":true},"taxonomy":{"id":1,"title":"Post","alias":"post","contents":[],"fields":[],"metas":[]},"categories":[{"id":2,"title":"Non cat\u00e9goris\u00e9","lft":2,"rgt":3,"parent":{"id":1,"title":"Root","lft":1,"rgt":4,"ordre":1,"level":0,"children":[],"contents":[],"url":"root-html","translations":[],"published":false,"metavalues":[]},"ordre":2,"level":1,"children":[],"language":{"id":1,"name":"Fran\u00e7ais","code_local":"fr_fr","code_lang":"fr_fr","sens_ecriture":"ltr","ordre":0,"default":true},"contents":[],"url":"non-categorise-html","translations":[],"published":true,"metavalues":[]}],"created":"2016-05-10T17:24:16+0200","modified":"2016-05-10T17:24:16+0200","published":true,"url":"test-article","translations":[],"fieldvalues":[],"metavalues":[],"author":{"id":1,"user_login":"admin","user_nicename":"Damien Corona","user_email":"leoncorono@gmail.com"},"thumbnail":""}]};

	app.controller('contentListController', function($scope, $http) {
        var that = this;
		$http.get('/rest/contents').then(function(response) {
            that.contents = response.data.contents;
            console.log(that.contents[0].title);
        });
	});
})();