<?php

namespace Novuso\Test\Common\Application\Container\Exception;

use Novuso\Common\Application\Container\Exception\EntryNotFoundException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Container\Exception\EntryNotFoundException
 */
class EntryNotFoundExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Entry not found';
        $exception = new EntryNotFoundException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new EntryNotFoundException('Entry not found');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Container\\Exception\\ServiceContainerException',
            $exception
        );
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new EntryNotFoundException('Entry not found');
        $this->assertSame(1501, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new EntryNotFoundException('Entry not found', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = EntryNotFoundException::create('Entry not found');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Container\\Exception\\EntryNotFoundException',
            $exception
        );
    }
}
