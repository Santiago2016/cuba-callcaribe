'use strict'

angular.module('asterisk.directives',[]);

angular.module('asterisk.directives').directive('appVersion',['version',function(version){
	return {
		restrict: 'AE',
		link: function(scope,elem,attrs){
			elem.html(version);
		}
	}	
}]);
