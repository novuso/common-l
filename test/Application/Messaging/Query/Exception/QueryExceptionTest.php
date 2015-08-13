<?php

namespace Novuso\Test\Common\Application\Messaging\Query\Exception;

use Novuso\Common\Application\Messaging\Query\Exception\QueryException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\Exception\QueryException
 */
class QueryExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Query error';
        $exception = new QueryException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new QueryException('Query error');
        $this->assertInstanceOf('Exception', $exception);
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new QueryException('Query error');
        $this->assertSame(1120, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new QueryException('Query error', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = QueryException::create('Query error');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Query\\Exception\\QueryException',
            $exception
        );
    }
}
