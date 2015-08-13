<?php

namespace Novuso\Common\Application\Messaging\Command\Resolver;

use Novuso\Common\Application\Messaging\Command\Exception\HandlerNotFoundException;
use Novuso\Common\Application\Messaging\Command\Exception\InvalidCommandException;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * CommandMemoryMap is a command class to handler instance map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class CommandMemoryMap
{
    /**
     * Handler map
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Registers command handlers
     *
     * The command to handler map must follow this format:
     * [
     *     SomeCommand::class => $someHandlerInstance
     * ]
     *
     * @param array $commandToHandlerMap A map of class names to handlers
     *
     * @return void
     *
     * @throws InvalidCommandException When a command class is not valid
     */
    public function registerHandlers(array $commandToHandlerMap)
    {
        foreach ($commandToHandlerMap as $commandClass => $handler) {
            $this->registerHandler($commandClass, $handler);
        }
    }

    /**
     * Registers a command handler
     *
     * @param string         $commandClass The full command class name
     * @param CommandHandler $handler      The command handler
     *
     * @return void
     *
     * @throws InvalidCommandException When the command class is not valid
     */
    public function registerHandler($commandClass, CommandHandler $handler)
    {
        if (!Test::implementsInterface($commandClass, Command::class)) {
            $message = sprintf('Invalid command class: %s', $commandClass);
            throw InvalidCommandException::create($message);
        }

        $type = Type::create($commandClass)->toString();

        $this->handlers[$type] = $handler;
    }

    /**
     * Retrieves a handler by command class name
     *
     * @param string $commandClass The full command class name
     *
     * @return CommandHandler
     *
     * @throws HandlerNotFoundException When the handler is not found
     */
    public function getHandler($commandClass)
    {
        $type = Type::create($commandClass)->toString();

        if (!isset($this->handlers[$type])) {
            $message = sprintf('Handler not defined for command: %s', $commandClass);
            throw HandlerNotFoundException::create($message);
        }

        return $this->handlers[$type];
    }

    /**
     * Checks if a handler is defined for a command
     *
     * @param string $commandClass The full command class name
     *
     * @return bool
     */
    public function hasHandler($commandClass)
    {
        $type = Type::create($commandClass)->toString();

        return isset($this->handlers[$type]);
    }
}
