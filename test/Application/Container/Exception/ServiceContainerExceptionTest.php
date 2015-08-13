<?php

namespace Novuso\Test\Common\Application\Container\Exception;

use Novuso\Common\Application\Container\Exception\ServiceContainerException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Container\Exception\ServiceContainerException
 */
class ServiceContainerExceptionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_message_as_argument()
    {
        $message = 'Container error';
        $exception = new ServiceContainerException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function test_that_parent_exception_matches_expected()
    {
        $exception = new ServiceContainerException('Container error');
        $this->assertInstanceOf('Exception', $exception);
    }

    public function test_that_default_code_matches_expected()
    {
        $exception = new ServiceContainerException('Container error');
        $this->assertSame(1500, $exception->getCode());
    }

    public function test_that_default_code_can_be_overridden_in_constructor()
    {
        $exception = new ServiceContainerException('Container error', 1000);
        $this->assertSame(1000, $exception->getCode());
    }

    public function test_that_create_returns_exception_instance()
    {
        $exception = ServiceContainerException::create('Container error');
        $this->assertInstanceOf(
            'Novuso\\Common\\Application\\Container\\Exception\\ServiceContainerException',
            $exception
        );
    }
}
