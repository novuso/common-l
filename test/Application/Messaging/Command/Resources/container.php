<?php

use Novuso\Common\Application\Container\ServiceContainer;

$container = new ServiceContainer();

$container->service('logger', function () {
    return new Novuso\Test\Common\Doubles\Application\Logging\InMemoryLogger();
});

$container->service('command.pipeline', function ($container) {
    return new Novuso\Common\Application\Messaging\Command\CommandPipeline(
        $container->get('command.handler_resolver'),
        $container->getParameter('command.filters', [])
    );
});

$container->service('command.bus', function ($container) {
    return new Novuso\Common\Application\Messaging\Command\CommandHandlerBus(
        $container->get('command.handler_resolver')
    );
});

$container->service('command.handler_resolver', function ($container) {
    return new Novuso\Common\Application\Messaging\Command\Resolver\CommandServiceResolver(
        $container->get('command.service_map')
    );
});

$container->service('command.service_map', function ($container) {
    $serviceMap = new Novuso\Common\Application\Messaging\Command\Resolver\CommandServiceMap($container);
    $handlers = [];
    $command = 'Novuso\\Test\\Common\\Doubles\\Application\\Messaging\\Command\\CreateTaskCommand';
    $serviceId = 'command.handler.create_task';
    $handlers[$command] = $serviceId;
    $serviceMap->registerHandlers($handlers);

    return $serviceMap;
});

$container->service('command.filter.logger', function ($container) {
    return new Novuso\Common\Application\Messaging\Command\Filter\CommandLogger(
        $container->get('logger')
    );
});

$container->service('command.handler.create_task', function ($container) {
    return new Novuso\Test\Common\Doubles\Application\Messaging\Command\CreateTaskHandler(
        $container->get('event.dispatcher')
    );
});

$container->service('command.handler.error_task', function ($container) {
    return new Novuso\Test\Common\Doubles\Application\Messaging\Command\ErrorTaskHandler();
});

$container->service('event.dispatcher', function ($container) {
    $dispatcher = new Novuso\Common\Application\Messaging\Event\EventServiceDispatcher($container);
    $dispatcher->attachService(
        'test.subscriber',
        'Novuso\\Test\\Common\\Doubles\\Application\\Messaging\\Event\\TestSubscriber'
    );

    return $dispatcher;
});

$container->service('test.subscriber', function () {
    return new Novuso\Test\Common\Doubles\Application\Messaging\Event\TestSubscriber();
});

return $container;
