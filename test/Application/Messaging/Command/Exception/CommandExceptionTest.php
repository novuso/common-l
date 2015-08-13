<?php

namespace Novuso\Test\Common\Application\Messaging\Command\Exception;

use Novuso\Common\Application\Messaging\Command\Exception\CommandException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\Exception\CommandException
 */
class CommandExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Command error';
        $exception = new CommandException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new CommandException('Command error');
        $this->assertInstanceOf('Exception', $exception);
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new CommandException('Command error');
        $this->assertSame(1100, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new CommandException('Command error', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = CommandException::create('Command error');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Command\\Exception\\CommandException',
            $exception
        );
    }
}
