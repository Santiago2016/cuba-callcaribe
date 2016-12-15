'use strict'

angular.module('asterisk', ['ngCookies', 'ngSanitize', 'ngResource', 'ngAnimate', 'ui.router', 'pascalprecht.translate', 'asterisk.admin','asterisk.controllers', 'asterisk.directives', 'asterisk.filters', 'asterisk.services']);

angular.module('asterisk').config(['$translateProvider', '$httpProvider', function ($translateProvider, $httpProvider) {
        $translateProvider.translations('en', {
            TITLE: 'Asterisk',
            SUBTITLE: 'A place recharge',
        });

        $translateProvider.translations('es', {
            TITLE: 'Asterisk',
            SUBTITLE: 'Un lugar para recargar',
        });

        $translateProvider.preferredLanguage('en');

        $httpProvider.defaults.withCredentials = false;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
        $httpProvider.defaults.headers.common = {};
        $httpProvider.defaults.headers.post = {};
        $httpProvider.defaults.headers.put = {};
        $httpProvider.defaults.headers.delete = {};

    }]);

angular.module('asterisk').run(['$state', '$rootScope', '$translate', function ($state, $rootScope, $translate) {

        $state.go('login');

        $rootScope.languagePreference = {currentLanguage: 'en'};

        $rootScope.languagePreference.switchLanguage = function (key) {
            $translate.use(key);
            $rootScope.languagePreference.currentLanguage = key;
        }
    }]);
