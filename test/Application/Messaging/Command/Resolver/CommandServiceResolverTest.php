<?php

namespace Novuso\Test\Common\Application\Messaging\Command\Resolver;

use Novuso\Test\Common\Doubles\Application\Messaging\Command\CreateTaskCommand;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\Resolver\CommandServiceResolver
 */
class CommandServiceResolverTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require dirname(__DIR__).'/Resources/container.php';
    }

    public function test_that_it_can_resolve_the_command_handler()
    {
        $handler = $this->container->get('command.handler.create_task');
        $resolver = $this->container->get('command.handler_resolver');
        $command = new CreateTaskCommand('test');
        $this->assertSame($handler, $resolver->resolve($command));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Command\Exception\HandlerNotFoundException
     */
    public function test_that_resolve_throws_exception_when_unable_to_resolve_handler()
    {
        $serviceMap = $this->container->get('command.service_map');
        $serviceMap->registerHandlers([CreateTaskCommand::class => 'foo.bar.baz']);
        $resolver = $this->container->get('command.handler_resolver');
        $command = new CreateTaskCommand('test');
        $resolver->resolve($command);
    }
}
