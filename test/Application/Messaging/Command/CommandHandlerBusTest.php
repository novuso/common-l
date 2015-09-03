<?php

namespace Novuso\Test\Common\Application\Messaging\Command;

use Novuso\Test\Common\Doubles\Application\Messaging\Command\CreateTaskCommand;
use Novuso\System\Utility\Test;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\CommandHandlerBus
 */
class CommandHandlerBusTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require __DIR__.'/Resources/container.php';
    }

    public function test_that_command_results_in_handled_event()
    {
        $command = new CreateTaskCommand('test');
        $this->container->get('command.bus')->execute($command);
        $this->assertTrue(Test::isUuid($this->container->get('test.subscriber')->taskId()));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Command\Exception\CommandException
     */
    public function test_that_exceptions_are_thrown_as_command_exceptions()
    {
        $this->container->get('command.service_map')
            ->registerHandler(CreateTaskCommand::class, 'command.handler.error_task');
        $command = new CreateTaskCommand('test');
        $this->container->get('command.bus')->execute($command);
    }
}
