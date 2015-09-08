<?php

namespace Novuso\Test\Common\Application\Messaging\Command\Resolver;

use Novuso\Test\Common\Doubles\Application\Messaging\Command\CreateTaskCommand;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\Resolver\CommandServiceMap
 */
class CommandServiceMapTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require dirname(__DIR__).'/Resources/container.php';
    }

    public function test_that_it_returns_the_expected_handler_instance()
    {
        $handler = $this->container->get('command.handler.create_task');
        $serviceMap = $this->container->get('command.service_map');
        $this->assertSame($handler, $serviceMap->getHandler(CreateTaskCommand::class));
    }

    public function test_that_has_handler_returns_true_for_defined_command_handler()
    {
        $serviceMap = $this->container->get('command.service_map');
        $this->assertTrue($serviceMap->hasHandler(CreateTaskCommand::class));
    }

    public function test_that_has_handler_returns_false_for_undefined_command_class()
    {
        $serviceMap = $this->container->get('command.service_map');
        $this->assertFalse($serviceMap->hasHandler('FooBarBaz'));
    }

    public function test_that_has_handler_returns_false_for_undefined_service_id()
    {
        $serviceMap = $this->container->get('command.service_map');
        $serviceMap->registerHandlers([CreateTaskCommand::class => 'foo.bar.baz']);
        $this->assertFalse($serviceMap->hasHandler(CreateTaskCommand::class));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Command\Exception\HandlerNotFoundException
     */
    public function test_that_get_handler_throws_exception_for_undefined_command_class()
    {
        $serviceMap = $this->container->get('command.service_map');
        $serviceMap->getHandler('FooBarBaz');
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Command\Exception\HandlerNotFoundException
     */
    public function test_that_get_handler_throws_exception_for_undefined_service_id()
    {
        $serviceMap = $this->container->get('command.service_map');
        $serviceMap->registerHandlers([CreateTaskCommand::class => 'foo.bar.baz']);
        $serviceMap->getHandler(CreateTaskCommand::class);
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Command\Exception\InvalidCommandException
     */
    public function test_that_register_handler_throws_exception_for_invalid_command_class()
    {
        $serviceMap = $this->container->get('command.service_map');
        $serviceMap->registerHandlers(['FooBarBaz' => 'command.handler.create_task']);
    }
}
