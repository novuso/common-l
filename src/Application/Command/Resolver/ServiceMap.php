<?php

namespace Novuso\Common\Application\Command\Resolver;

use Novuso\Common\Application\Command\Command;
use Novuso\Common\Application\Command\Exception\HandlerNotFoundException;
use Novuso\Common\Application\Command\Exception\InvalidContractException;
use Novuso\Common\Application\Command\Handler;
use Novuso\Common\Application\Service\Container;
use Novuso\Common\Application\Service\Exception\ServiceException;
use Novuso\System\Type\Contract;
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
     * @param Container $container             The service container
     * @param array     $commandToServiceIdMap A map of class names to service IDs
     *
     * @throws InvalidContractException When a command class is not valid
     */
    public function __construct(Container $container, array $commandToServiceIdMap = [])
    {
        $this->container = $container;
        $this->addHandlers($commandToServiceIdMap);
    }

    /**
     * Registers command handlers
     *
     * The command to service map must follow this format:
     * [
     *     SomeCommand::class => "handler_service_id"
     * ]
     *
     * @param array $commandToServiceMap A map of class names to service IDs
     *
     * @return void
     *
     * @throws InvalidContractException When a command class is not valid
     */
    public function addHandlers(array $commandToServiceIdMap)
    {
        foreach ($commandToServiceIdMap as $commandClass => $serviceId) {
            $this->setHandler($commandClass, $serviceId);
        }
    }

    /**
     * Registers a handler
     *
     * @param string $commandClass The fully-qualified command class name
     * @param string $serviceId    The service ID for the handler
     *
     * @return void
     *
     * @throws InvalidContractException When a command class is not valid
     */
    public function setHandler($commandClass, $serviceId)
    {
        if (!Test::implementsInterface($commandClass, Command::class)) {
            $message = sprintf('Invalid command class: %s', $commandClass);
            throw InvalidContractException::create($message);
        }

        $contract = Contract::create($commandClass)->toString();

        $this->handlers[$contract] = $serviceId;
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
        $contract = Contract::create($commandClass)->toString();

        if (!isset($this->handlers[$contract])) {
            $message = sprintf('Handler not defined for command: %s', $commandClass);
            throw HandlerNotFoundException::create($message);
        }

        $serviceId = $this->handlers[$contract];

        try {
            $handler = $this->container->get($serviceId);
        } catch (ServiceException $exception) {
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
        $contract = Contract::create($commandClass)->toString();

        if (!isset($this->handlers[$contract])) {
            return false;
        }

        $serviceId = $this->handlers[$contract];

        return $this->container->has($serviceId);
    }
}
