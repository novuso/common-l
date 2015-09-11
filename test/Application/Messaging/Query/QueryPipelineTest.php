<?php

namespace Novuso\Test\Common\Application\Messaging\Query;

use Novuso\Test\Common\Doubles\Application\Messaging\Query\GetTaskQuery;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\QueryPipeline
 */
class QueryPipelineTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require __DIR__.'/Resources/container.php';
    }

    public function test_that_query_results_in_view_model()
    {
        $query = new GetTaskQuery('014faa02-b67c-4aa4-b063-ceaf27f4a9b3');
        $data = $this->container->get('query.pipeline')->fetch($query);
        $this->assertSame('Test task two', $data->description());
    }

    public function test_that_query_filters_can_be_added()
    {
        $this->container->setParameter('query.filters', [$this->container->get('query.filter.logger')]);
        $query = new GetTaskQuery('014faa02-b67c-4aa4-b063-ceaf27f4a9b3');
        $data = $this->container->get('query.pipeline')->fetch($query);
        $logger = $this->container->get('logger');
        $logs = $logger->logs();
        $expected1 = 'Query (GetTaskQuery) received';
        $expected2 = 'Query (GetTaskQuery) handled';
        $this->assertTrue(
            substr($logs[0]['message'], 0, 29) === $expected1 && substr($logs[1]['message'], 0, 28) === $expected2
        );
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Query\Exception\QueryException
     */
    public function test_that_exceptions_are_thrown_as_query_exceptions()
    {
        $query = new GetTaskQuery('014faa07-826e-4773-8aad-e8d9bef7617c');
        $data = $this->container->get('query.pipeline')->fetch($query);
    }
}
