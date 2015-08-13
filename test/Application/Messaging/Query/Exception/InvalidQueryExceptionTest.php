<?php

namespace Novuso\Test\Common\Application\Messaging\Query\Exception;

use Novuso\Common\Application\Messaging\Query\Exception\InvalidQueryException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\Exception\InvalidQueryException
 */
class InvalidQueryExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Invalid query';
        $exception = new InvalidQueryException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new InvalidQueryException('Invalid query');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Query\\Exception\\QueryException',
            $exception
        );
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new InvalidQueryException('Invalid query');
        $this->assertSame(1121, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new InvalidQueryException('Invalid query', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = InvalidQueryException::create('Invalid query');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Query\\Exception\\InvalidQueryException',
            $exception
        );
    }
}
