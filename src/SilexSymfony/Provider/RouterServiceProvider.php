<?php

namespace SilexSymfony\Provider;

use Silex\Application;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use SilexSymfony\Routing\Router;
use Silex\ServiceProviderInterface;

class RouterServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $app['router'] = $app->share(function ($app) {
            $defaults = array(
                'debug'              => $app['debug'],
                'matcher_base_class' => 'Silex\\RedirectableUrlMatcher',
                'matcher_class'      => 'Silex\\RedirectableUrlMatcher',
            );

            $config = $app['router.resource'];
            $loader = new YamlFileLoader(
                new FileLocator([dirname($config)])
            );

            $options = array_replace($defaults, $app['router.options']);

            $router = new Router($loader, $config, $options, $app['request_context'], $app['logger']);
            $router->setRoutes($app['routes']);

            return $router;
        });

        $app['url_matcher'] = $app->share(function ($app) {
            return $app['router'];
        });

        $app['url_generator'] = $app->share(function ($app) {
            return $app['router'];
        });
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
    }
}
