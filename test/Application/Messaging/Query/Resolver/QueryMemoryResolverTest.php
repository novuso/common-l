<?php

namespace Novuso\Test\Common\Application\Messaging\Query\Resolver;

use Novuso\Common\Application\Messaging\Query\Resolver\QueryMemoryMap;
use Novuso\Common\Application\Messaging\Query\Resolver\QueryMemoryResolver;
use Novuso\Test\Common\Doubles\Application\Messaging\Query\GetTaskQuery;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\Resolver\QueryMemoryResolver
 */
class QueryMemoryResolverTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require dirname(__DIR__).'/Resources/container.php';
    }

    public function test_that_it_can_resolve_the_query_handler()
    {
        $handler = $this->container->get('query.handler.get_task');
        $queryMap = new QueryMemoryMap();
        $queryMap->registerHandlers([GetTaskQuery::class => $handler]);
        $resolver = new QueryMemoryResolver($queryMap);
        $query = new GetTaskQuery('014faa02-b67c-4aa4-b063-ceaf27f4a9b3');
        $this->assertSame($handler, $resolver->resolve($query));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Query\Exception\HandlerNotFoundException
     */
    public function test_that_resolve_throws_exception_when_unable_to_resolve_handler()
    {
        $queryMap = new QueryMemoryMap();
        $resolver = new QueryMemoryResolver($queryMap);
        $query = new GetTaskQuery('014faa02-b67c-4aa4-b063-ceaf27f4a9b3');
        $resolver->resolve($query);
    }
}
