<?php

namespace Novuso\Test\Common\Application\Messaging\Event\Exception;

use Novuso\Common\Application\Messaging\Event\Exception\EventException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Event\Exception\EventException
 */
class EventExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Event error';
        $exception = new EventException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new EventException('Event error');
        $this->assertInstanceOf('Exception', $exception);
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new EventException('Event error');
        $this->assertSame(1110, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new EventException('Event error', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = EventException::create('Event error');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Event\\Exception\\EventException',
            $exception
        );
    }
}
