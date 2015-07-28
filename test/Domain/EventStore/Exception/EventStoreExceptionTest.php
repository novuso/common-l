<?php

namespace Novuso\Test\Common\Domain\EventStore\Exception;

use Novuso\Common\Domain\EventStore\Exception\EventStoreException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventStore\Exception\EventStoreException
 */
class EventStoreExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Something went wrong';
        $exception = new EventStoreException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new EventStoreException('Something went wrong');
        $this->assertInstanceOf('Exception', $exception);
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new EventStoreException('Something went wrong');
        $this->assertSame(500, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new EventStoreException('Something went wrong', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = EventStoreException::create('Something went wrong');
        $this->assertInstanceOf('Novuso\\Common\\Domain\\EventStore\\Exception\\EventStoreException', $exception);
    }
}
