'use strict'

angular.module('asterisk.admin.services', []).service('popupService',['$window',function($window){
    this.showPopup=function(message){
        return $window.confirm(message); //Ask the users if they really want to delete the post entry
    }
}]).factory('authService',['AUTH_ENDPOINT','LOGOUT_ENDPOINT','$http','$cookieStore',function(AUTH_ENDPOINT,LOGOUT_ENDPOINT,$http,$cookieStore){

    var auth={};

    auth.login=function(username,password){
        return $http.post(AUTH_ENDPOINT,{username:username,password:password}).then(function(response,status){
            auth.user=response.data.user;
            if (auth.user != null){
                $cookieStore.put('user',auth.user);
                return auth.user;
            }
            return null;
        });
    }

    auth.logout=function(){
        return $http.post(LOGOUT_ENDPOINT).then(function(response){
            auth.user=undefined;
            $cookieStore.remove('user');
        });
    }

    return auth;

}]);


angular.module('asterisk.admin.services').value('AUTH_ENDPOINT','http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/login');
angular.module('asterisk.admin.services').value('LOGOUT_ENDPOINT','http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/logout');