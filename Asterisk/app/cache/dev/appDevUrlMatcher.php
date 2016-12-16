<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appDevUrlMatcher.
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appDevUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        if (0 === strpos($pathinfo, '/_')) {
            // _wdt
            if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_wdt')), array (  '_controller' => 'web_profiler.controller.profiler:toolbarAction',));
            }

            if (0 === strpos($pathinfo, '/_profiler')) {
                // _profiler_home
                if (rtrim($pathinfo, '/') === '/_profiler') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_profiler_home');
                    }

                    return array (  '_controller' => 'web_profiler.controller.profiler:homeAction',  '_route' => '_profiler_home',);
                }

                if (0 === strpos($pathinfo, '/_profiler/search')) {
                    // _profiler_search
                    if ($pathinfo === '/_profiler/search') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchAction',  '_route' => '_profiler_search',);
                    }

                    // _profiler_search_bar
                    if ($pathinfo === '/_profiler/search_bar') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchBarAction',  '_route' => '_profiler_search_bar',);
                    }

                }

                // _profiler_purge
                if ($pathinfo === '/_profiler/purge') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:purgeAction',  '_route' => '_profiler_purge',);
                }

                // _profiler_info
                if (0 === strpos($pathinfo, '/_profiler/info') && preg_match('#^/_profiler/info/(?P<about>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_info')), array (  '_controller' => 'web_profiler.controller.profiler:infoAction',));
                }

                // _profiler_phpinfo
                if ($pathinfo === '/_profiler/phpinfo') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:phpinfoAction',  '_route' => '_profiler_phpinfo',);
                }

                // _profiler_search_results
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/search/results$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_search_results')), array (  '_controller' => 'web_profiler.controller.profiler:searchResultsAction',));
                }

                // _profiler
                if (preg_match('#^/_profiler/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler')), array (  '_controller' => 'web_profiler.controller.profiler:panelAction',));
                }

                // _profiler_router
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/router$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_router')), array (  '_controller' => 'web_profiler.controller.router:panelAction',));
                }

                // _profiler_exception
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception')), array (  '_controller' => 'web_profiler.controller.exception:showAction',));
                }

                // _profiler_exception_css
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception\\.css$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception_css')), array (  '_controller' => 'web_profiler.controller.exception:cssAction',));
                }

            }

            if (0 === strpos($pathinfo, '/_configurator')) {
                // _configurator_home
                if (rtrim($pathinfo, '/') === '/_configurator') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_configurator_home');
                    }

                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::checkAction',  '_route' => '_configurator_home',);
                }

                // _configurator_step
                if (0 === strpos($pathinfo, '/_configurator/step') && preg_match('#^/_configurator/step/(?P<index>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_configurator_step')), array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::stepAction',));
                }

                // _configurator_final
                if ($pathinfo === '/_configurator/final') {
                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::finalAction',  '_route' => '_configurator_final',);
                }

            }

        }

        if (0 === strpos($pathinfo, '/api')) {
            if (0 === strpos($pathinfo, '/api/log')) {
                // api_rest_login
                if ($pathinfo === '/api/login') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_api_rest_login;
                    }

                    return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\LoginController::loginUserAction',  '_fotmat' => NULL,  '_route' => 'api_rest_login',);
                }
                not_api_rest_login:

                // api_rest_logout
                if ($pathinfo === '/api/logout') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_api_rest_logout;
                    }

                    return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\LoginController::logoutUserAction',  '_fotmat' => NULL,  '_route' => 'api_rest_logout',);
                }
                not_api_rest_logout:

            }

            // api_rest_forgot
            if ($pathinfo === '/api/forgot') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_api_rest_forgot;
                }

                return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\LoginController::forgotAction',  '_fotmat' => NULL,  '_route' => 'api_rest_forgot',);
            }
            not_api_rest_forgot:

            // api_rest_user_update
            if ($pathinfo === '/api/user/update') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_api_rest_user_update;
                }

                return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\LoginController::updateUserAction',  '_fotmat' => NULL,  '_route' => 'api_rest_user_update',);
            }
            not_api_rest_user_update:

            // api_rest_password_update
            if ($pathinfo === '/api/password/update') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_api_rest_password_update;
                }

                return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\LoginController::updatePasswordAction',  '_fotmat' => NULL,  '_route' => 'api_rest_password_update',);
            }
            not_api_rest_password_update:

            if (0 === strpos($pathinfo, '/api/admin')) {
                if (0 === strpos($pathinfo, '/api/admin/recarga')) {
                    // api_rest_admin_recargas
                    if ($pathinfo === '/api/admin/recargas') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_api_rest_admin_recargas;
                        }

                        return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\AdminController::recargasAction',  '_format' => NULL,  '_route' => 'api_rest_admin_recargas',);
                    }
                    not_api_rest_admin_recargas:

                    // api_rest_admin_recargar
                    if ($pathinfo === '/api/admin/recargar') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_api_rest_admin_recargar;
                        }

                        return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\AdminController::recargarAction',  '_format' => NULL,  '_route' => 'api_rest_admin_recargar',);
                    }
                    not_api_rest_admin_recargar:

                }

                // api_rest_admin_llamadas
                if ($pathinfo === '/api/admin/llamadas') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_api_rest_admin_llamadas;
                    }

                    return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\AdminController::llamadasAction',  '_format' => NULL,  '_route' => 'api_rest_admin_llamadas',);
                }
                not_api_rest_admin_llamadas:

                // api_rest_admin_tiendas
                if ($pathinfo === '/api/admin/tiendas') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_api_rest_admin_tiendas;
                    }

                    return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\AdminController::tiendasAction',  '_format' => NULL,  '_route' => 'api_rest_admin_tiendas',);
                }
                not_api_rest_admin_tiendas:

            }

            if (0 === strpos($pathinfo, '/api/tienda')) {
                if (0 === strpos($pathinfo, '/api/tienda/recarga')) {
                    // api_rest_tienda_recargas
                    if ($pathinfo === '/api/tienda/recargas') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_api_rest_tienda_recargas;
                        }

                        return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\TiendaController::recargasAction',  '_format' => NULL,  '_route' => 'api_rest_tienda_recargas',);
                    }
                    not_api_rest_tienda_recargas:

                    // api_rest_tienda_recargar
                    if ($pathinfo === '/api/tienda/recargar') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_api_rest_tienda_recargar;
                        }

                        return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\TiendaController::recargarAction',  '_format' => NULL,  '_route' => 'api_rest_tienda_recargar',);
                    }
                    not_api_rest_tienda_recargar:

                }

                // api_rest_tienda_llamadas
                if ($pathinfo === '/api/tienda/llamadas') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_api_rest_tienda_llamadas;
                    }

                    return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\TiendaController::llamadasAction',  '_format' => NULL,  '_route' => 'api_rest_tienda_llamadas',);
                }
                not_api_rest_tienda_llamadas:

                // api_rest_tienda_clientes
                if ($pathinfo === '/api/tienda/clientes') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_api_rest_tienda_clientes;
                    }

                    return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\TiendaController::clientesAction',  '_format' => NULL,  '_route' => 'api_rest_tienda_clientes',);
                }
                not_api_rest_tienda_clientes:

            }

            if (0 === strpos($pathinfo, '/api/cliente')) {
                // api_rest_cliente_recargas
                if ($pathinfo === '/api/cliente/recargas') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_api_rest_cliente_recargas;
                    }

                    return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\ClienteController::recargasAction',  '_format' => NULL,  '_route' => 'api_rest_cliente_recargas',);
                }
                not_api_rest_cliente_recargas:

                // api_rest_cliente_llamdas
                if ($pathinfo === '/api/cliente/llamadas') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_api_rest_cliente_llamdas;
                    }

                    return array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\CLienteController::llamadasAction',  '_format' => NULL,  '_route' => 'api_rest_cliente_llamdas',);
                }
                not_api_rest_cliente_llamdas:

            }

            if (0 === strpos($pathinfo, '/api/users')) {
                // api_users_get_user
                if (preg_match('#^/api/users/(?P<id>[^/\\.]++)(?:\\.(?P<_format>json|html))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_api_users_get_user;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'api_users_get_user')), array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\UsersController::getUserAction',  '_format' => NULL,));
                }
                not_api_users_get_user:

                // api_users_get_users
                if (preg_match('#^/api/users(?:\\.(?P<_format>json|html))?$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_api_users_get_users;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'api_users_get_users')), array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\UsersController::getUsersAction',  '_format' => NULL,));
                }
                not_api_users_get_users:

                // api_users_delete_user
                if (preg_match('#^/api/users/(?P<id>[^/\\.]++)(?:\\.(?P<_format>json|html))?$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'DELETE') {
                        $allow[] = 'DELETE';
                        goto not_api_users_delete_user;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'api_users_delete_user')), array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\UsersController::deleteUserAction',  '_format' => NULL,));
                }
                not_api_users_delete_user:

                // api_users_post_user
                if (preg_match('#^/api/users(?:\\.(?P<_format>json|html))?$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_api_users_post_user;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'api_users_post_user')), array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\UsersController::postUserAction',  '_format' => NULL,));
                }
                not_api_users_post_user:

                // api_users_put_user
                if (preg_match('#^/api/users/(?P<id>[^/\\.]++)(?:\\.(?P<_format>json|html))?$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'PUT') {
                        $allow[] = 'PUT';
                        goto not_api_users_put_user;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'api_users_put_user')), array (  '_controller' => 'Admin\\ApiRestBundle\\Controller\\UsersController::putUserAction',  '_format' => NULL,));
                }
                not_api_users_put_user:

            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
