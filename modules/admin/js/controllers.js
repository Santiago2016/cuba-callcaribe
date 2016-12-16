'use strict'

angular.module('asterisk.admin.controllers', ['ab-base64'])



.controller('LoginController', ['$scope', 'authService', '$state', function ($scope, authService, $state) {

    $scope.buttonText = "Login";

    $scope.login = function () {

        $scope.buttonText = "Logging in. . .";

        authService.login($scope.credentials.username, $scope.credentials.password).then(function (data) {
            if (data == null) {
                $scope.invalidLogin = true;
            } else {
                $state.go('cliente.recargas');
            }
        }, function (err) {
            $scope.invalidLogin = true;
        }).finally(function () {
            $scope.buttonText = "Login";
        });
    }
}])



.controller('RegistrationController',['$scope','$state','$http',function($scope,$state,$http){
    $scope.register = function(){
        if ($scope.credentials.password != $scope.credentials.password_confirmation){
            $scope.passwordIncorrect = true;
        }
        else{
             $http.post('Asterisk/web/app_dev.php/api/users',{
                user: 35,
                username: $scope.credentials.user,
                password: $scope.credentials.password,
                role: 'ROLE_CLIENTE',
                nombre: $scope.credentials.name,
                email: $scope.credentials.email,
                telefono: $scope.credentials.phone
            }).then(function (response) {
                    $state.go('login');
            }, function (err) {
                $scope.err = err;
            });
        }
       
    }
}])



.controller('ForgotController', ['$scope', '$state', '$http', function ($scope, $state, $http) {
    $scope.buttonText = "Aceptar";
    $scope.forgot = function () {
        $http.post('Asterisk/web/app_dev.php/api/forgot', {
            username: $scope.credentials.username,
            nombre: $scope.credentials.nombre,
            email: $scope.credentials.email,
            telefono: $scope.credentials.telefono
        }).then(function (response) {
            var response = response.data;
            if (response.status == 'success') {
                $state.go('login');
            } else {
                $scope.invalidforgot = true;
            }
        });
    }
}])



.controller('ClienteController', ['$scope', 'authService', '$state', 'user', function ($scope, authService, $state, user) {
    $scope.user = user;
    $scope.logout = function () {
        authService.logout().then(function () {
            $state.go('login');
        });
    }
}])


.controller('ClienteRecargasController', ['$scope', '$http', function ($scope, $http) {
    $http.post('Asterisk/web/app_dev.php/api/cliente/recargas').then(function (response) {
        $scope.response = response.data;
    });
}])


.controller('ClienteLLamadasController', ['$scope', '$http', function ($scope, $http) {
    $http.post('Asterisk/web/app_dev.php/api/cliente/llamadas').then(function (response) {
        $scope.response = response.data;
    });
}])


.controller('ClienteRecargarController', ['$scope', '$http', 'user', '$state', function ($scope, $http, user, $state) {
    $scope.recargar = function () {
        $http.post('Asterisk/web/app_dev.php/api/cliente/recargar', {
            user: user.id,
            tarjeta: $scope.tarjeta,
            codigo: $scope.codigo,
            importe: $scope.importe
        }).then(function (response) {
            $state.go('cliente.recargas');
        }, function (err) {
            $scope.response = err;
        })
    }
}])


.controller('PasswordController', ['$scope', '$http', 'user', '$state', function ($scope, $http, user, $state) {
    $scope.user = user;
    $scope.updatePassword = function (){
        if ($scope.user.passwordnueva == $scope.user.passwordnueva1) {
            $http.post('Asterisk/web/app_dev.php/api/password/update', {
                id: $scope.user.id,
                passwordvieja: $scope.user.passwordvieja,
                passwordnueva: $scope.user.passwordnueva
            }).then(function (response) {
                var retorno = response.data.user;
                if (retorno.role == 'ROLE_ADMIN'){
                $state.go('admin.recargas');
                }else if (retorno.role == 'ROLE_TIENDA'){
                    $state.go('tienda.recargas');
                }else{
                    $state.go('cliente.recargas');
                }
            })
        }else{
            $scope.passwordnoequals = true;
        }
    }
}])


.controller('ClientePerfilController', ['$scope', '$http', 'user', '$state',function ($scope, $http, user, $state) {
    $scope.user = user;
    $scope.updatePerfil = function (){
        $http.post('Asterisk/web/app_dev.php/api/user/update', {
            id: $scope.user.id,
            username: $scope.user.username,
            role: $scope.user.role,
            nombre: $scope.user.nombre,
            email: $scope.user.email,
            telefono: $scope.user.telefono
        }).then(function (response) {
            $state.go('cliente.recargas');
        })
    }
}]);