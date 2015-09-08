<?php

namespace Novuso\Test\Common\Application\Messaging\Query;

use Novuso\Test\Common\Doubles\Application\Messaging\Query\GetTaskQuery;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\QueryHandlerService
 */
class QueryHandlerServiceTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require __DIR__.'/Resources/container.php';
    }

    public function test_that_query_results_in_view_model()
    {
        $query = new GetTaskQuery('014faa02-b67c-4aa4-b063-ceaf27f4a9b3');
        $viewModel = $this->container->get('query.service')->fetch($query);
        $this->assertSame('Test task two', $viewModel->description());
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Query\Exception\QueryException
     */
    public function test_that_exceptions_are_thrown_as_query_exceptions()
    {
        $query = new GetTaskQuery('014faa07-826e-4773-8aad-e8d9bef7617c');
        $viewModel = $this->container->get('query.service')->fetch($query);
    }
}
