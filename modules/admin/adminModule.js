'use strict'

angular.module('asterisk.admin', ['asterisk.admin.controllers', 'asterisk.admin.directives', 'asterisk.admin.services', 'asterisk.admin.filters']);

angular.module('asterisk.admin').config(['$stateProvider', function ($stateProvider) {
    $stateProvider.state('login', {
        url: '/login',
        controller: 'LoginController',
        resolve: {
            user: ['authService', '$q', function (authService, $q) {
                if (authService.user) {
                    return $q.reject({authorized: true});
                }
            }]
        },
        templateUrl: 'modules/admin/views/login.html'
    }).state('register', {
        url: '/register',
        controller: 'RegistrationController',
        resolve: {
            user: ['authService', '$q', function (authService, $q) {
                if (authService.user) {
                    return $q.reject({authorized: true});
                }
            }]
        },
        templateUrl: 'modules/admin/views/register.html'
    }).state('forgot', {
        url: '/forgot',
        controller: 'ForgotController',
        templateUrl: 'modules/admin/views/forgot-password.html'
    }).state('cliente', {
        url: '/cliente',
        abstract: true,
        controller: 'ClienteController',
        resolve: {
            user: ['authService', '$q', function (authService, $q) {
                return authService.user || $q.reject({unAuthorized: true});
            }]
        },
        templateUrl: 'modules/admin/views/cliente-home.html'
    }).state('password', {
        url: '/set/password',
        controller: 'PasswordController',
        resolve: {
            user: ['authService', '$q', function (authService, $q) {
                return authService.user || $q.reject({unAuthorized: true});
            }]
        },
        templateUrl: 'modules/admin/views/password.html'
    }).state('cliente.recargas', {
        url: '/recargas',
        controller: 'ClienteRecargasController',
        templateUrl: 'modules/admin/views/cliente-recargas.html'
    }).state('cliente.llamadas', {
        url: '/llamadas',
        controller: 'ClienteLLamadasController',
        templateUrl: 'modules/admin/views/cliente-llamadas.html'
    }).state('cliente.perfil', {
        url: '/perfil',
        controller: 'ClientePerfilController',
        templateUrl: 'modules/admin/views/cliente-perfil.html'
    }).state('cliente.recarga', {
        url: '/recargar',
        controller: 'ClienteRecargarController',
        templateUrl: 'modules/admin/views/cliente-recargar.html'
    });
}]).run(['$rootScope', '$state', '$cookieStore', 'authService', function ($rootScope, $state, $cookieStore, authService) {

    $rootScope.$on('$stateChangeError', function (event, toState, toParams, fromState, fromParams, error) {

        if (error.unAuthorized) {
            $state.go('login');
        } else if (error.authorized) {
            $state.go('admin.recargas');
        }
    });

    authService.user = $cookieStore.get('user');

}]);
