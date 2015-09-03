<?php

namespace Novuso\Test\Common\Application\Messaging\Command;

use Novuso\Test\Common\Doubles\Application\Messaging\Command\CreateTaskCommand;
use Novuso\System\Utility\Test;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\CommandPipeline
 */
class CommandPipelineTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require __DIR__.'/Resources/container.php';
    }

    public function test_that_command_results_in_handled_event()
    {
        $command = new CreateTaskCommand('test');
        $this->container->get('command.pipeline')->execute($command);
        $this->assertTrue(Test::isUuid($this->container->get('test.subscriber')->taskId()));
    }

    public function test_that_command_filters_can_be_added()
    {
        $this->container->setParameter('command.filters', [$this->container->get('command.filter.logger')]);
        $command = new CreateTaskCommand('test');
        $this->container->get('command.pipeline')->execute($command);
        $logger = $this->container->get('logger');
        $logs = $logger->logs();
        $expected1 = 'Command (CreateTaskCommand) received';
        $expected2 = 'Command (CreateTaskCommand) handled';
        $this->assertTrue(
            substr($logs[0]['message'], 0, 36) === $expected1 && substr($logs[1]['message'], 0, 35) === $expected2
        );
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Command\Exception\CommandException
     */
    public function test_that_exceptions_are_thrown_as_command_exceptions()
    {
        $this->container->get('command.service_map')
            ->registerHandler(CreateTaskCommand::class, 'command.handler.error_task');
        $command = new CreateTaskCommand('test');
        $this->container->get('command.pipeline')->execute($command);
    }
}
