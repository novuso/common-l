<?php

use Novuso\Common\Application\Container\ServiceContainer;
use Novuso\Test\Common\Doubles\Domain\Model\Task;
use Novuso\Test\Common\Doubles\Domain\Model\TaskId;

$container = new ServiceContainer();

$container->service('logger', function () {
    return new Novuso\Test\Common\Doubles\Application\Logging\InMemoryLogger();
});

$container->service('query.pipeline', function ($container) {
    return new Novuso\Common\Application\Messaging\Query\QueryPipeline(
        $container->get('query.handler_resolver'),
        $container->getParameter('query.filters', [])
    );
});

$container->service('query.service', function ($container) {
    return new Novuso\Common\Application\Messaging\Query\QueryHandlerService(
        $container->get('query.handler_resolver')
    );
});

$container->service('query.handler_resolver', function ($container) {
    return new Novuso\Common\Application\Messaging\Query\Resolver\QueryServiceResolver(
        $container->get('query.service_map')
    );
});

$container->service('query.service_map', function ($container) {
    $serviceMap = new Novuso\Common\Application\Messaging\Query\Resolver\QueryServiceMap($container);
    $handlers = [];
    $query = 'Novuso\\Test\\Common\\Doubles\\Application\\Messaging\\Query\\GetTaskQuery';
    $serviceId = 'query.handler.get_task';
    $handlers[$query] = $serviceId;
    $serviceMap->registerHandlers($handlers);

    return $serviceMap;
});

$container->service('query.filter.logger', function ($container) {
    return new Novuso\Common\Application\Messaging\Query\Filter\QueryLogger(
        $container->get('logger')
    );
});

$container->service('query.handler.get_task', function ($container) {
    $tasks = [
        Task::reconstitute(TaskId::fromString('014faa02-b67b-4beb-9d68-10abbb4455cb'), 'Test task one', 0),
        Task::reconstitute(TaskId::fromString('014faa02-b67c-4aa4-b063-ceaf27f4a9b3'), 'Test task two', 0),
        Task::reconstitute(TaskId::fromString('014faa02-b67e-47e1-b1da-5bbdc7e342b3'), 'Test task three', 0)
    ];

    return new Novuso\Test\Common\Doubles\Application\Messaging\Query\GetTaskHandler($tasks);
});

return $container;
