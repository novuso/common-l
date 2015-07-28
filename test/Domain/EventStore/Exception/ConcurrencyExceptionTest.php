<?php

namespace Novuso\Test\Common\Domain\EventStore\Exception;

use Novuso\Common\Domain\EventStore\Exception\ConcurrencyException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventStore\Exception\ConcurrencyException
 */
class ConcurrencyExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Concurrency violation';
        $exception = new ConcurrencyException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new ConcurrencyException('Concurrency violation');
        $this->assertInstanceOf('Novuso\\Common\\Domain\\EventStore\\Exception\\EventStoreException', $exception);
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new ConcurrencyException('Concurrency violation');
        $this->assertSame(502, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new ConcurrencyException('Concurrency violation', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = ConcurrencyException::create('Concurrency violation');
        $this->assertInstanceOf('Novuso\\Common\\Domain\\EventStore\\Exception\\ConcurrencyException', $exception);
    }
}
