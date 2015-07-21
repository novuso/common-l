<?php

namespace Novuso\Common\Application\Command\Resolver;

use Novuso\Common\Application\Command\Command;
use Novuso\Common\Application\Command\Handler;
use Novuso\Common\Application\Command\Exception\HandlerNotFoundException;
use Novuso\Common\Application\Command\Exception\InvalidCommandException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * InMemoryMap is a command class to handler instance map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class InMemoryMap
{
    /**
     * Handler map
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Constructs InMemoryMap
     *
     * The command to handler map must follow this format:
     * [
     *     SomeCommand::class => $someHandlerInstance
     * ]
     *
     * @param array $commandToHandlerMap A map of class names to handlers
     *
     * @throws InvalidCommandException When a command class is not valid
     */
    public function __construct(array $commandToHandlerMap = [])
    {
        $this->addHandlers($commandToHandlerMap);
    }

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
    public function addHandlers(array $commandToHandlerMap)
    {
        foreach ($commandToHandlerMap as $commandClass => $handler) {
            $this->setHandler($commandClass, $handler);
        }
    }

    /**
     * Registers a command handler
     *
     * @param string  $commandClass The fully-qualified command class name
     * @param Handler $handler      The handler
     *
     * @return void
     *
     * @throws InvalidCommandException When the command class is not valid
     */
    public function setHandler($commandClass, Handler $handler)
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
     * @param string $commandClass The fully-qualified command class name
     *
     * @return Handler
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
     * @param string $commandClass The fully-qualified command class name
     *
     * @return bool
     */
    public function hasHandler($commandClass)
    {
        $type = Type::create($commandClass)->toString();

        return isset($this->handlers[$type]);
    }
}
