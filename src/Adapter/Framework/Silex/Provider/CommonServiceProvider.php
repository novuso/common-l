<?php

namespace Novuso\Common\Adapter\Framework\Silex\Provider;

use Novuso\Common\Adapter\Infrastructure\Logging\PsrLogger;
use Novuso\Common\Adapter\Infrastructure\Service\PimpleContainer;
use Novuso\Common\Application\Command\Pipeline\ApplicationBus;
use Novuso\Common\Application\Command\Pipeline\CommandLogger;
use Novuso\Common\Application\Command\Pipeline\CommandPipeline;
use Novuso\Common\Application\Command\Resolver\ServiceMap;
use Novuso\Common\Application\Command\Resolver\ServiceResolver;
use Novuso\Common\Application\Event\ServiceAwareDispatcher;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * CommonServiceProvider provides common services for the application
 *
 * This service provider depends on another service provider that registers a
 * PSR-3 compatible logger to the container as 'logger'.
 *
 * Silex comes with such a provider: Silex\Provider\MonologServiceProvider
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class CommonServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container
     *
     * @param Container $app A Container instance
     *
     * @return void
     */
    public function register(Container $app)
    {
        // Novuso\Common\Application\Service\Container
        $app['novuso_common.service_container'] = function ($app) {
            return new PimpleContainer($app);
        };

        // Novuso\Common\Application\Logging\Logger
        $app['novuso_common.logger'] = function ($app) {
            return new PsrLogger($app['logger']);
        };

        // Novuso\Common\Domain\Event\Dispatcher
        $app['novuso_common.event_dispatcher'] = function ($app) {
            $dispatcher = new ServiceAwareDispatcher($app['novuso_common.service_container']);

            foreach ($app['novuso_common.event_subscribers'] as $serviceId => $className) {
                $dispatcher->attachService($serviceId, $className);
            }

            return $dispatcher;
        };

        // Novuso\Common\Application\Command\CommandBus
        $app['novuso_common.command_bus'] = function () use ($app) {
            $pipeline = new CommandPipeline($app['novuso_common.application_bus']);

            foreach ($app['novuso_common.command_middleware'] as $serviceId) {
                $pipeline->addMiddleware($app[$serviceId]);
            }

            return $pipeline;
        };

        // Novuso\Common\Application\Command\Resolver\ServiceMap
        $app['novuso_common.command_resolver.service_map'] = function ($app) {
            $serviceMap = new ServiceMap($app['novuso_common.service_container']);

            foreach ($app['novuso_common.command_handlers'] as $serviceId => $commandClass) {
                $serviceMap->setHandler($commandClass, $serviceId);
            }

            return $serviceMap;
        };

        $app['novuso_common.application_bus'] = function ($app) {
            return new ApplicationBus($app['novuso_common.command_resolver']);
        };

        $app['novuso_common.command_resolver'] = function ($app) {
            return new ServiceResolver($app['novuso_common.command_resolver.service_map']);
        };

        $app['novuso_common.command_logger'] = function ($app) {
            return new CommandLogger($app['novuso_common.logger']);
        };

        // Register command middleware services
        // ['middleware_service_id']
        $app['novuso_common.command_middleware'] = ['novuso_common.command_logger'];

        // Register command handler services
        // ['handler_service_id' => 'Command\\Class']
        $app['novuso_common.command_handlers'] = [];

        // Register domain event subscribers
        // ['subscriber_service_id' => 'Subscriber\\Class']
        $app['novuso_common.event_subscribers'] = [];
    }
}
