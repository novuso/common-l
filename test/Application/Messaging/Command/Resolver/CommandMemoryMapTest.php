<?php

namespace Novuso\Test\Common\Application\Messaging\Command\Resolver;

use Novuso\Common\Application\Messaging\Command\Resolver\CommandMemoryMap;
use Novuso\Test\Common\Doubles\Application\Messaging\Command\CreateTaskCommand;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\Resolver\CommandMemoryMap
 */
class CommandMemoryMapTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require dirname(__DIR__).'/Resources/container.php';
    }

    public function test_that_it_can_register_handlers()
    {
        $handler = $this->container->get('command.handler.create_task');
        $commandMap = new CommandMemoryMap();
        $commandMap->registerHandlers([CreateTaskCommand::class => $handler]);
        $this->assertTrue($commandMap->hasHandler(CreateTaskCommand::class));
    }

    public function test_that_it_returns_the_expected_handler_instance()
    {
        $handler = $this->container->get('command.handler.create_task');
        $commandMap = new CommandMemoryMap();
        $commandMap->registerHandlers([CreateTaskCommand::class => $handler]);
        $this->assertSame($handler, $commandMap->getHandler(CreateTaskCommand::class));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Command\Exception\HandlerNotFoundException
     */
    public function test_that_get_handler_throws_exception_for_undefined_command_class()
    {
        $commandMap = new CommandMemoryMap();
        $commandMap->getHandler(CreateTaskCommand::class);
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Command\Exception\InvalidCommandException
     */
    public function test_that_register_handler_throws_exception_for_invalid_command_class()
    {
        $handler = $this->container->get('command.handler.create_task');
        $commandMap = new CommandMemoryMap();
        $commandMap->registerHandlers(['FooBarBaz' => $handler]);
    }
}
