<?php

namespace Novuso\Test\Common\Application\Messaging\Command\Resolver;

use Novuso\Common\Application\Messaging\Command\Resolver\CommandMemoryMap;
use Novuso\Common\Application\Messaging\Command\Resolver\CommandMemoryResolver;
use Novuso\Test\Common\Doubles\Application\Messaging\Command\CreateTaskCommand;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\Resolver\CommandMemoryResolver
 */
class CommandMemoryResolverTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require dirname(__DIR__).'/Resources/container.php';
    }

    public function test_that_it_can_resolve_the_command_handler()
    {
        $handler = $this->container->get('command.handler.create_task');
        $commandMap = new CommandMemoryMap();
        $commandMap->registerHandlers([CreateTaskCommand::class => $handler]);
        $resolver = new CommandMemoryResolver($commandMap);
        $command = new CreateTaskCommand('test');
        $this->assertSame($handler, $resolver->resolve($command));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Command\Exception\HandlerNotFoundException
     */
    public function test_that_resolve_throws_exception_when_unable_to_resolve_handler()
    {
        $commandMap = new CommandMemoryMap();
        $resolver = new CommandMemoryResolver($commandMap);
        $command = new CreateTaskCommand('test');
        $resolver->resolve($command);
    }
}
