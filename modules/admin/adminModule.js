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
    }).state('forgot', {
        url: '/forgot',
        controller: 'ForgotController',
        templateUrl: 'modules/admin/views/forgot-password.html'
    }).state('admin', {
        url: '/admin',
        abstract: true,
        controller: 'AdminController',
        resolve: {
            user: ['authService', '$q', function (authService, $q) {
                return authService.user || $q.reject({unAuthorized: true});
            }]
        },
        templateUrl: 'modules/admin/views/admin-home.html'
    }).state('tienda', {
        url: '/tienda',
        abstract: true,
        controller: 'TiendaController',
        resolve: {
            user: ['authService', '$q', function (authService, $q) {
                return authService.user || $q.reject({unAuthorized: true});
            }]
        },
        templateUrl: 'modules/admin/views/tienda-home.html'
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
    }).state('admin.recargar', {
        url: '/recargar',
        controller: 'AdminRecargarController',
        templateUrl: 'modules/admin/views/admin-recargar.html'
    }).state('admin.recargas', {
        url: '/recargas',
        controller: 'AdminRecargasController',
        templateUrl: 'modules/admin/views/admin-recargas.html'
    }).state('admin.llamadas', {
        url: '/llamadas',
        controller: 'AdminLLamadasController',
        templateUrl: 'modules/admin/views/admin-llamadas.html'
    }).state('admin.perfil', {
        url: '/perfil',
        controller: 'AdminPerfilController',
        templateUrl: 'modules/admin/views/admin-perfil.html'
    }).state('admin.tiendas', {
        url: '/tiendas',
        controller: 'AdminTiendasController',
        templateUrl: 'modules/admin/views/admin-tiendas.html'
    }).state('admin.creartienda', {
        url: '/tiendas/crear',
        controller: 'AdminCrearTiendaController',
        templateUrl: 'modules/admin/views/admin-tiendas-crear.html'
    }).state('admin.editartienda', {
        url: '/tiendas/:id/editar',
        controller: 'AdminEditarTiendaController',
        templateUrl: 'modules/admin/views/admin-tiendas-editar.html'
    }).state('tienda.recargar', {
        url: '/recargar',
        controller: 'TiendaRecargarController',
        templateUrl: 'modules/admin/views/tienda-recargar.html'
    }).state('tienda.recargas', {
        url: '/recargas',
        controller: 'TiendaRecargasController',
        templateUrl: 'modules/admin/views/tienda-recargas.html'
    }).state('tienda.llamadas', {
        url: '/llamadas',
        controller: 'TiendaLLamadasController',
        templateUrl: 'modules/admin/views/tienda-llamadas.html'
    }).state('tienda.perfil', {
        url: '/perfil',
        controller: 'TiendaPerfilController',
        templateUrl: 'modules/admin/views/tienda-perfil.html'
    }).state('tienda.clientes', {
        url: '/clientes',
        controller: 'TiendaClientesController',
        templateUrl: 'modules/admin/views/tienda-clientes.html'
    }).state('tienda.crearcliente', {
        url: '/clientes/crear',
        controller: 'TiendaCrearClienteController',
        templateUrl: 'modules/admin/views/tienda-clientes-crear.html'
    }).state('tienda.editarcliente', {
        url: '/clientes/:id/editar',
        controller: 'TiendaEditarClienteController',
        templateUrl: 'modules/admin/views/tienda-clientes-editar.html'
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
