<?php

namespace Novuso\Common\Application\Messaging\Command\Filter;

use Exception;
use Novuso\Common\Application\Messaging\Command\Exception\CommandException;
use Novuso\Common\Application\Logging\Logger;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandFilter;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\System\Utility\ClassName;

/**
 * CommandLogger is a filter that logs command messages
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CommandLogger implements CommandFilter
{
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
    public function process(CommandMessage $message, callable $next)
    {
        $command = ClassName::short($message->payloadType()->toString());

        try {
            $this->logger->debug(
                sprintf('Command (%s) received: %s', $command, date(DATE_ATOM)),
                ['message' => $message->toString()]
            );

            $next($message);

            $this->logger->debug(
                sprintf('Command (%s) handled: %s', $command, date(DATE_ATOM)),
                ['message' => $message->toString()]
            );
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Command (%s) failed: %s', $command, date(DATE_ATOM)),
                ['message' => $message->toString(), 'exception' => $exception]
            );
            throw CommandException::create($exception->getMessage(), $exception);
        }
    }
}
