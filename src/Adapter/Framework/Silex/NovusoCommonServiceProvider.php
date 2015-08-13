<?php

namespace Novuso\Common\Adapter\Framework\Silex;

use Novuso\Common\Adapter\Infrastructure\Container\PimpleContainer;
use Novuso\Common\Adapter\Infrastructure\Logging\PsrLogger;
use Novuso\Common\Adapter\Presentation\Symfony\Http\Resolver\ResponderServiceMap;
use Novuso\Common\Adapter\Presentation\Symfony\Http\Resolver\ResponderServiceResolver;
use Novuso\Common\Adapter\Presentation\Symfony\Http\Subscriber\ResponderSubscriber;
use Novuso\Common\Application\Messaging\Command\CommandHandlerBus;
use Novuso\Common\Application\Messaging\Command\CommandPipeline;
use Novuso\Common\Application\Messaging\Command\Resolver\CommandServiceMap;
use Novuso\Common\Application\Messaging\Command\Resolver\CommandServiceResolver;
use Novuso\Common\Application\Messaging\Event\EventServiceDispatcher;
use Novuso\Common\Application\Messaging\Query\QueryHandlerService;
use Novuso\Common\Application\Messaging\Query\QueryPipeline;
use Novuso\Common\Application\Messaging\Query\Resolver\QueryServiceMap;
use Novuso\Common\Application\Messaging\Query\Resolver\QueryServiceResolver;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * NovusoCommonServiceProvider provides common services for the application
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
class NovusoCommonServiceProvider implements EventListenerProviderInterface, ServiceProviderInterface
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

        // Novuso\Common\Application\Messaging\Event\Dispatcher
        $app['novuso_common.event_dispatcher'] = function ($app) {
            $dispatcher = new EventServiceDispatcher($app['novuso_common.service_container']);

            foreach ($app['novuso_common.event_subscribers'] as $serviceId => $className) {
                $dispatcher->attachService($serviceId, $className);
            }

            return $dispatcher;
        };

        // Novuso\Common\Application\Messaging\Command\CommandBus
        $app['novuso_common.command_bus'] = function ($app) {
            return new CommandPipeline(
                $app['novuso_common.command_handler_bus'],
                $app['novuso_common.command_filters']
            );
        };

        // Novuso\Common\Application\Messaging\Query\QueryService
        $app['novuso_common.query_service'] = function ($app) {
            return new QueryPipeline(
                $app['novuso_common.query_handler_service'],
                $app['novuso_common.query_filters']
            );
        };

        $app['novuso_common.command_handler_bus'] = function ($app) {
            return new CommandHandlerBus($app['novuso_common.command_service_resolver']);
        };

        $app['novuso_common.command_service_resolver'] = function ($app) {
            return new CommandServiceResolver($app['novuso_common.command_service_map']);
        };

        $app['novuso_common.command_service_map'] = function ($app) {
            $commandServiceMap = new CommandServiceMap($app['novuso_common.service_container']);

            foreach ($app['novuso_common.command_handlers'] as $serviceId => $commandClass) {
                $commandServiceMap->registerHandler($commandClass, $serviceId);
            }

            return $commandServiceMap;
        };

        $app['novuso_common.query_handler_service'] = function ($app) {
            return new QueryHandlerService($app['novuso_common.query_service_resolver']);
        };

        $app['novuso_common.query_service_resolver'] = function ($app) {
            return new QueryServiceResolver($app['novuso_common.query_service_map']);
        };

        $app['novuso_common.query_service_map'] = function ($app) {
            $queryServiceMap = new QueryServiceMap($app['novuso_common.service_container']);

            foreach ($app['novuso_common.query_handlers'] as $serviceId => $queryClass) {
                $queryServiceMap->registerHandler($queryClass, $serviceId);
            }

            return $queryServiceMap;
        };

        $app['novuso_common.responder_subscriber'] = function ($app) {
            return new ResponderSubscriber($app['novuso_common.responder_service_resolver']);
        };

        $app['novuso_common.responder_service_resolver'] = function ($app) {
            return new ResponderServiceResolver($app['novuso_common.responder_service_map']);
        };

        $app['novuso_common.responder_service_map'] = function ($app) {
            $responderServiceMap = new ResponderServiceMap($app['novuso_common.service_container']);

            foreach ($app['novuso_common.responders'] as $serviceId => $actionClass) {
                $responderServiceMap->registerResponder($actionClass, $serviceId);
            }

            return $responderServiceMap;
        };

        // List of command filter instances
        // [$commandFilter1, $commandFilter2]
        // Command filter must implement:
        // Novuso\Common\Domain\Messaging\Command\CommandFilter
        $app['novuso_common.command_filters'] = [];

        // List of query filter instances
        // [$queryFilter1, $queryFilter2]
        // Query filter must implement:
        // Novuso\Common\Domain\Messaging\Query\QueryFilter
        $app['novuso_common.query_filters'] = [];

        // Map of command handler services
        // ['handler_service_id' => 'Command\\ClassName']
        // Command handler must implement:
        // Novuso\Common\Domain\Messaging\Command\CommandHandler
        $app['novuso_common.command_handlers'] = [];

        // Map of query handler services
        // ['handler_service_id' => 'Query\\ClassName']
        // Query handler must implement:
        // Novuso\Common\Domain\Messaging\Query\QueryHandler
        $app['novuso_common.query_handlers'] = [];

        // Map of domain event subscribers
        // ['subscriber_service_id' => 'Subscriber\\ClassName']
        // Event subscriber must implement:
        // Novuso\Common\Domain\Messaging\Event\Subscriber
        $app['novuso_common.event_subscribers'] = [];

        // Map of HTTP responder services
        // ['responder_service_id' => 'Action\\ClassName']
        // Responder must extend:
        // Novuso\Common\Adapter\Presentation\Symfony\Http\Responder
        $app['novuso_common.responders'] = [];
    }

    /**
     * Registers event listeners with the Silex dispatcher
     *
     * @param Container                $app        The application instance
     * @param EventDispatcherInterface $dispatcher The event dispatcher
     *
     * @return void
     */
    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber($app['novuso_common.responder_subscriber']);
    }
}
