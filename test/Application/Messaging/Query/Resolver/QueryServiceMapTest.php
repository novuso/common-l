<?php

namespace Novuso\Test\Common\Application\Messaging\Query\Resolver;

use Novuso\Test\Common\Doubles\Application\Messaging\Query\GetTaskQuery;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\Resolver\QueryServiceMap
 */
class QueryServiceMapTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require dirname(__DIR__).'/Resources/container.php';
    }

    public function test_that_it_returns_the_expected_handler_instance()
    {
        $handler = $this->container->get('query.handler.get_task');
        $serviceMap = $this->container->get('query.service_map');
        $this->assertSame($handler, $serviceMap->getHandler(GetTaskQuery::class));
    }

    public function test_that_has_handler_returns_true_for_defined_query_handler()
    {
        $serviceMap = $this->container->get('query.service_map');
        $this->assertTrue($serviceMap->hasHandler(GetTaskQuery::class));
    }

    public function test_that_has_handler_returns_false_for_undefined_query_class()
    {
        $serviceMap = $this->container->get('query.service_map');
        $this->assertFalse($serviceMap->hasHandler('FooBarBaz'));
    }

    public function test_that_has_handler_returns_false_for_undefined_service_id()
    {
        $serviceMap = $this->container->get('query.service_map');
        $serviceMap->registerHandlers([GetTaskQuery::class => 'foo.bar.baz']);
        $this->assertFalse($serviceMap->hasHandler(GetTaskQuery::class));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Query\Exception\HandlerNotFoundException
     */
    public function test_that_get_handler_throws_exception_for_undefined_query_class()
    {
        $serviceMap = $this->container->get('query.service_map');
        $serviceMap->getHandler('FooBarBaz');
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Query\Exception\HandlerNotFoundException
     */
    public function test_that_get_handler_throws_exception_for_undefined_service_id()
    {
        $serviceMap = $this->container->get('query.service_map');
        $serviceMap->registerHandlers([GetTaskQuery::class => 'foo.bar.baz']);
        $serviceMap->getHandler(GetTaskQuery::class);
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Query\Exception\InvalidQueryException
     */
    public function test_that_register_handler_throws_exception_for_invalid_query_class()
    {
        $serviceMap = $this->container->get('query.service_map');
        $serviceMap->registerHandlers(['FooBarBaz' => 'query.handler.get_task']);
    }
}
