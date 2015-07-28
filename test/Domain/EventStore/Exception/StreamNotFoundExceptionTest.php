<?php

namespace Novuso\Test\Common\Domain\EventStore\Exception;

use Novuso\Common\Domain\EventStore\Exception\StreamNotFoundException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventStore\Exception\StreamNotFoundException
 */
class StreamNotFoundExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Stream not found';
        $exception = new StreamNotFoundException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new StreamNotFoundException('Stream not found');
        $this->assertInstanceOf('Novuso\\Common\\Domain\\EventStore\\Exception\\EventStoreException', $exception);
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new StreamNotFoundException('Stream not found');
        $this->assertSame(501, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new StreamNotFoundException('Stream not found', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = StreamNotFoundException::create('Stream not found');
        $this->assertInstanceOf('Novuso\\Common\\Domain\\EventStore\\Exception\\StreamNotFoundException', $exception);
    }
}
