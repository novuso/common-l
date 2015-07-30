<?php

namespace Novuso\Common\Adapter\Framework\Silex\Provider;

use Novuso\Common\Adapter\Infrastructure\Logging\PsrLogger;
use Novuso\Common\Adapter\Infrastructure\Container\PimpleContainer;
use Novuso\Common\Adapter\Presentation\Resolver\ResponderServiceResolver;
use Novuso\Common\Adapter\Presentation\Subscriber\ViewSubscriber;
use Novuso\Common\Application\Command\Pipeline\ApplicationBus;
use Novuso\Common\Application\Command\Pipeline\CommandPipeline;
use Novuso\Common\Application\Command\Resolver\ServiceMap;
use Novuso\Common\Application\Command\Resolver\ServiceResolver;
use Novuso\Common\Application\Event\ServiceAwareDispatcher;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
class CommonServiceProvider implements EventListenerProviderInterface, ServiceProviderInterface
{
    /**
     * Registers services on the given container
     *
     * @param Container $app The application instance
     *
     * @return void
     */
    public function register(Container $app)
    {
        // Novuso\Common\Application\Container\Container
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
        $app['novuso_common.command_bus'] = function ($app) {
            return new CommandPipeline(
                $app['novuso_common.application_bus'],
                $app['novuso_common.command_filters']
            );
        };

        // Novuso\Common\Application\Command\Resolver\ServiceMap
        $app['novuso_common.command_resolver.service_map'] = function ($app) {
            return new ServiceMap(
                $app['novuso_common.service_container'],
                $app['novuso_common.command_handlers']
            );
        };

        $app['novuso_common.application_bus'] = function ($app) {
            return new ApplicationBus($app['novuso_common.command_resolver']);
        };

        $app['novuso_common.command_resolver'] = function ($app) {
            return new ServiceResolver($app['novuso_common.command_resolver.service_map']);
        };

        $app['novuso_common.view_subscriber'] = function ($app) {
            return new ViewSubscriber($app['novuso_common.responder_resolver']);
        };

        $app['novuso_common.responder_resolver'] = function ($app) {
            return new ResponderServiceResolver(
                $app['novuso_common.service_container'],
                $app['novuso_common.view_responders']
            );
        };

        // List of Novuso\Common\Application\Command\Filter instances
        // [$commandFilter1, $commandFilter2]
        $app['novuso_common.command_filters'] = [];

        // Register command handler services
        // ['handler_service_id' => 'Command\\Class']
        $app['novuso_common.command_handlers'] = [];

        // Register presentation view responders
        // ['responder_service_id' => 'Action\\Class']
        $app['novuso_common.view_responders'] = [];

        // Register domain event subscribers
        // ['subscriber_service_id' => 'Subscriber\\Class']
        $app['novuso_common.event_subscribers'] = [];
    }

    /**
     * Registers event subscribers with the Silex dispatcher
     *
     * @param Container                $app        The application instance
     * @param EventDispatcherInterface $dispatcher The event dispatcher
     *
     * @return void
     */
    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber($app['novuso_common.view_subscriber']);
    }
}
