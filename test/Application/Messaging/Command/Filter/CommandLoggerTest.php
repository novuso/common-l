<?php

namespace Novuso\Test\Common\Application\Messaging\Command\Filter;

use Exception;
use Novuso\Test\Common\Doubles\Application\Messaging\Command\CreateTaskCommand;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Command\Filter\CommandLogger
 */
class CommandLoggerTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require dirname(__DIR__).'/Resources/container.php';
    }

    public function test_that_command_logger_records_successful_command()
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

    public function test_that_command_logger_records_command_exception()
    {
        try {
            $this->container->get('command.service_map')
                ->registerHandler(CreateTaskCommand::class, 'command.handler.error_task');
            $this->container->setParameter('command.filters', [$this->container->get('command.filter.logger')]);
            $command = new CreateTaskCommand('test');
            $this->container->get('command.pipeline')->execute($command);
        } catch (Exception $exception) {
            $logger = $this->container->get('logger');
            $logs = $logger->logs();
            $expected1 = 'Command (CreateTaskCommand) received';
            $expected2 = 'Command (CreateTaskCommand) failed';
            $this->assertTrue(
                substr($logs[0]['message'], 0, 36) === $expected1 && substr($logs[1]['message'], 0, 34) === $expected2
            );
        }
    }
}
