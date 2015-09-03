<?php

namespace Novuso\Common\Application\Messaging\Command\Resolver;

use Novuso\Common\Application\Container\Container;
use Novuso\Common\Application\Container\Exception\ServiceContainerException;
use Novuso\Common\Application\Messaging\Command\Exception\HandlerNotFoundException;
use Novuso\Common\Application\Messaging\Command\Exception\InvalidCommandException;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * CommandServiceMap is a command class to handler service map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CommandServiceMap
{
    /**
     * Service container
     *
     * @var Container
     */
    protected $container;

    /**
     * Handler map
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Constructs CommandServiceMap
     *
     * @param Container $container The service container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Registers command handlers
     *
     * The command to handler map must follow this format:
     * [
     *     SomeCommand::class => 'handler_service_id'
     * ]
     *
     * @param array $commandToHandlerMap A map of class names to service IDs
     *
     * @return void
     *
     * @throws InvalidCommandException When a command class is not valid
     */
    public function registerHandlers(array $commandToHandlerMap)
    {
        foreach ($commandToHandlerMap as $commandClass => $serviceId) {
            $this->registerHandler($commandClass, $serviceId);
        }
    }

    /**
     * Registers a command handler
     *
     * @param string $commandClass The full command class name
     * @param string $serviceId    The handler service ID
     *
     * @return void
     *
     * @throws InvalidCommandException When the command class is not valid
     */
    public function registerHandler($commandClass, $serviceId)
    {
        if (!Test::implementsInterface($commandClass, Command::class)) {
            $message = sprintf('Invalid command class: %s', $commandClass);
            throw InvalidCommandException::create($message);
        }

        $type = Type::create($commandClass)->toString();

        $this->handlers[$type] = (string) $serviceId;
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

        $serviceId = $this->handlers[$type];

        try {
            $handler = $this->container->get($serviceId);
        } catch (ServiceContainerException $exception) {
            throw HandlerNotFoundException::create($exception->getMessage(), $exception);
        }

        return $handler;
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

        if (!isset($this->handlers[$type])) {
            return false;
        }

        $serviceId = $this->handlers[$type];

        return $this->container->has($serviceId);
    }
}
