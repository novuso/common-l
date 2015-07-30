<?php

namespace Novuso\Common\Application\Command\Resolver;

use Novuso\Common\Application\Command\Command;
use Novuso\Common\Application\Command\Exception\HandlerNotFoundException;
use Novuso\Common\Application\Command\Exception\InvalidCommandException;
use Novuso\Common\Application\Command\Handler;
use Novuso\Common\Application\Container\Container;
use Novuso\Common\Application\Container\Exception\ServiceContainerException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * ServiceMap is a command class to handler service map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ServiceMap
{
    /**
     * Service container
     *
     * @var Container
     */
    protected $container;

    /**
     * Handler service map
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Constructs ServiceMap
     *
     * @param Container $container         The service container
     * @param array     $handlerServiceMap A map of handler services
     *
     * @throws InvalidCommandException When a command class is not valid
     */
    public function __construct(Container $container, array $handlerServiceMap = [])
    {
        $this->container = $container;
        $this->addHandlers($handlerServiceMap);
    }

    /**
     * Registers command handlers
     *
     * The command to service map must follow this format:
     * [
     *     "handler_service_id" => SomeCommand::class
     * ]
     *
     * @param array $handlerServiceMap A map of handler services
     *
     * @return void
     *
     * @throws InvalidCommandException When a command class is not valid
     */
    public function addHandlers(array $handlerServiceMap)
    {
        foreach ($handlerServiceMap as $serviceId => $commandClass) {
            $this->setHandler($serviceId, $commandClass);
        }
    }

    /**
     * Registers a handler
     *
     * @param string $serviceId    The service ID for the handler
     * @param string $commandClass The fully-qualified command class name
     *
     * @return void
     *
     * @throws InvalidCommandException When a command class is not valid
     */
    public function setHandler($serviceId, $commandClass)
    {
        if (!Test::implementsInterface($commandClass, Command::class)) {
            $message = sprintf('Invalid command class: %s', $commandClass);
            throw InvalidCommandException::create($message);
        }

        $type = Type::create($commandClass)->toString();

        $this->handlers[$type] = $serviceId;
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
     * @param string $commandClass The fully-qualified command class name
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
