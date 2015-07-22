<?php

namespace Novuso\Common\Application\Command\Filter;

use Novuso\Common\Application\Command\Command;
use Novuso\Common\Application\Command\Filter;
use Novuso\Common\Application\Command\Exception\CommandException;
use Novuso\Common\Application\Logging\Logger;
use Novuso\System\Serialization\Serializer;
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
     * Logger
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Serializer
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * Constructs CommandLogger
     *
     * @param Logger     $logger     The logger
     * @param Serializer $serializer A command serializer
     */
    public function __construct(Logger $logger, Serializer $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Command $command, callable $next)
    {
        try {
            $this->logger->info(
                sprintf('Command (%s) received: %s', ClassName::short($command), date(DATE_ATOM)),
                ['command' => $this->serializer->serialize($command)]
            );
            $next($command);
            $this->logger->info(
                sprintf('Command (%s) handled: %s', ClassName::short($command), date(DATE_ATOM)),
                ['command' => $this->serializer->serialize($command)]
            );
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Command (%s) failed: %s', ClassName::short($command), date(DATE_ATOM)),
                ['command' => $this->serializer->serialize($command), 'exception' => $exception]
            );
            throw CommandException::create($exception->getMessage(), $exception);
        }
    }
}
