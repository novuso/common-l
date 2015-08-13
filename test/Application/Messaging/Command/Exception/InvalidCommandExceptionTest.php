<?php

namespace Novuso\Test\Common\Application\Messaging\Command\Exception;

use Novuso\Common\Application\Messaging\Command\Exception\InvalidCommandException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\Exception\InvalidCommandException
 */
class InvalidCommandExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Invalid command';
        $exception = new InvalidCommandException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new InvalidCommandException('Invalid command');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Command\\Exception\\CommandException',
            $exception
        );
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new InvalidCommandException('Invalid command');
        $this->assertSame(1101, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new InvalidCommandException('Invalid command', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = InvalidCommandException::create('Invalid command');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Command\\Exception\\InvalidCommandException',
            $exception
        );
    }
}
