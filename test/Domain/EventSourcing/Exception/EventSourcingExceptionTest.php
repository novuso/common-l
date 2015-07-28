<?php

namespace Novuso\Test\Common\Domain\EventSourcing\Exception;

use Novuso\Common\Domain\EventSourcing\Exception\EventSourcingException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventSourcing\Exception\EventSourcingException
 */
class EventSourcingExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Something went wrong';
        $exception = new EventSourcingException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new EventSourcingException('Something went wrong');
        $this->assertInstanceOf('Exception', $exception);
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new EventSourcingException('Something went wrong');
        $this->assertSame(600, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new EventSourcingException('Something went wrong', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = EventSourcingException::create('Something went wrong');
        $this->assertInstanceOf(
            'Novuso\\Common\\Domain\\EventSourcing\\Exception\\EventSourcingException',
            $exception
        );
    }
}
