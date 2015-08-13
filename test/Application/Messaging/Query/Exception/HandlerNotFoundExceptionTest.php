<?php

namespace Novuso\Test\Common\Application\Messaging\Query\Exception;

use Novuso\Common\Application\Messaging\Query\Exception\HandlerNotFoundException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Query\Exception\HandlerNotFoundException
 */
class HandlerNotFoundExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Handler not found';
        $exception = new HandlerNotFoundException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new HandlerNotFoundException('Handler not found');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Query\\Exception\\QueryException',
            $exception
        );
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new HandlerNotFoundException('Handler not found');
        $this->assertSame(1122, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new HandlerNotFoundException('Handler not found', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = HandlerNotFoundException::create('Handler not found');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Messaging\\Query\\Exception\\HandlerNotFoundException',
            $exception
        );
    }
}
