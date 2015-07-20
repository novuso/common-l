<?php

namespace Novuso\Common\Application\Command\Pipeline;

use Novuso\Common\Application\Command\Command;
use Novuso\Common\Application\Command\CommandBus;
use Novuso\Common\Application\Command\Filter;
use Novuso\Common\Application\Command\Exception\CommandException;
use Novuso\Common\Application\Logging\Logger;
use Novuso\System\Utility\ClassName;

/**
 * CommandLogger is a filter that logs application commands
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class CommandLogger implements Filter
{
    /**
     * Command bus
     *
     * @var CommandBus
     */
    protected $outbound;

    /**
     * Logger
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Constructs CommandLogger
     *
     * @param Logger $logger The logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function setOutbound(CommandBus $outbound)
    {
        $this->outbound = $outbound;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command)
    {
        try {
            $this->logger->info(
                sprintf('Command received: %s at %s', ClassName::short($command), date(DATE_ATOM)),
                ['command' => $command->serialize()]
            );
            $this->outbound->execute($command);
            $this->logger->info(
                sprintf('Command acknowledged: %s at %s', ClassName::short($command), date(DATE_ATOM)),
                ['command' => $command->serialize()]
            );
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Command failed: %s at %s', ClassName::short($command), date(DATE_ATOM)),
                ['command' => $command->serialize(), 'exception' => $exception]
            );
            throw CommandException::create($exception->getMessage(), $exception);
        }
    }
}
