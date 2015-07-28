<?php

namespace Novuso\Test\Common\Domain\EventSourcing\Exception;

use Novuso\Common\Domain\EventSourcing\Exception\RegisterAggregateException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventSourcing\Exception\RegisterAggregateException
 */
class RegisterAggregateExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Aggregate registration error';
        $exception = new RegisterAggregateException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new RegisterAggregateException('Aggregate registration error');
        $this->assertInstanceOf(
            'Novuso\\Common\\Domain\\EventSourcing\\Exception\\EventSourcingException',
            $exception
        );
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new RegisterAggregateException('Aggregate registration error');
        $this->assertSame(601, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new RegisterAggregateException('Aggregate registration error', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = RegisterAggregateException::create('Aggregate registration error');
        $this->assertInstanceOf(
            'Novuso\\Common\\Domain\\EventSourcing\\Exception\\RegisterAggregateException',
            $exception
        );
    }
}
