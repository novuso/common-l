<?php

use Novuso\Common\Application\Container\ServiceContainer;

$container = new ServiceContainer();

$container->service('event.dispatcher', function ($container) {
    return new Novuso\Common\Application\Messaging\Event\EventServiceDispatcher($container);
});

$container->service('test.subscriber', function () {
    return new Novuso\Test\Common\Doubles\Application\Messaging\Event\TestSubscriber();
});

return $container;
