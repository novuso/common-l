<?php

namespace Novuso\Test\Common\Application\Messaging\Query\Filter;

use Exception;
use Novuso\Test\Common\Doubles\Application\Messaging\Query\GetTaskQuery;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\Filter\QueryLogger
 */
class QueryLoggerTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require dirname(__DIR__).'/Resources/container.php';
    }

    public function test_that_query_logger_records_successful_query()
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

    public function test_that_query_logger_records_query_exception()
    {
        try {
            $this->container->setParameter('query.filters', [$this->container->get('query.filter.logger')]);
            $query = new GetTaskQuery('014faa07-826e-4773-8aad-e8d9bef7617c');
            $data = $this->container->get('query.pipeline')->fetch($query);
        } catch (Exception $exception) {
            $logger = $this->container->get('logger');
            $logs = $logger->logs();
            $expected1 = 'Query (GetTaskQuery) received';
            $expected2 = 'Query (GetTaskQuery) failed';
            $this->assertTrue(
                substr($logs[0]['message'], 0, 29) === $expected1 && substr($logs[1]['message'], 0, 27) === $expected2
            );
        }
    }
}
