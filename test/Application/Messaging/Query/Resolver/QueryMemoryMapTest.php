<?php

namespace Novuso\Test\Common\Application\Messaging\Query\Resolver;

use Novuso\Common\Application\Messaging\Query\Resolver\QueryMemoryMap;
use Novuso\Test\Common\Doubles\Application\Messaging\Query\GetTaskQuery;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\Resolver\QueryMemoryMap
 */
class QueryMemoryMapTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require dirname(__DIR__).'/Resources/container.php';
    }

    public function test_that_it_can_register_handlers()
    {
        $handler = $this->container->get('query.handler.get_task');
        $queryMap = new QueryMemoryMap();
        $queryMap->registerHandlers([GetTaskQuery::class => $handler]);
        $this->assertTrue($queryMap->hasHandler(GetTaskQuery::class));
    }

    public function test_that_it_returns_the_expected_handler_instance()
    {
        $handler = $this->container->get('query.handler.get_task');
        $queryMap = new QueryMemoryMap();
        $queryMap->registerHandlers([GetTaskQuery::class => $handler]);
        $this->assertSame($handler, $queryMap->getHandler(GetTaskQuery::class));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Query\Exception\HandlerNotFoundException
     */
    public function test_that_get_handler_throws_exception_for_undefined_query_class()
    {
        $queryMap = new QueryMemoryMap();
        $queryMap->getHandler(GetTaskQuery::class);
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Query\Exception\InvalidQueryException
     */
    public function test_that_register_handler_throws_exception_for_invalid_query_class()
    {
        $handler = $this->container->get('query.handler.get_task');
        $queryMap = new QueryMemoryMap();
        $queryMap->registerHandlers(['FooBarBaz' => $handler]);
    }
}
