<?php

namespace Novuso\Test\Common\Application\Messaging\Event\Exception;

use Novuso\Common\Application\Messaging\Event\Exception\FrozenDispatcherException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Event\Exception\FrozenDispatcherException
 */
class FrozenDispatcherExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Frozen dispatcher';
        $exception = new FrozenDispatcherException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new FrozenDispatcherException('Frozen dispatcher');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Event\\Exception\\EventException',
            $exception
        );
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new FrozenDispatcherException('Frozen dispatcher');
        $this->assertSame(1111, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new FrozenDispatcherException('Frozen dispatcher', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = FrozenDispatcherException::create('Frozen dispatcher');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Event\\Exception\\FrozenDispatcherException',
            $exception
        );
    }
}
