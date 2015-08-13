<?php

namespace Novuso\Common\Adapter\Presentation\Symfony\Http\Resolver;

use LogicException;
use Novuso\Common\Adapter\Presentation\Symfony\Http\Action;
use Novuso\Common\Adapter\Presentation\Symfony\Http\Responder;
use Novuso\Common\Application\Container\Container;
use Novuso\Common\Application\Container\Exception\ServiceContainerException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * ResponderServiceMap is an action class to responder service map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class ResponderServiceMap
{
    /**
     * Service container
     *
     * @var Container
     */
    protected $container;

    /**
     * Responder map
     *
     * @var array
     */
    protected $responders = [];

    /**
     * Constructs ResponderServiceMap
     *
     * @param Container $container The service container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Registers HTTP responders
     *
     * The action to responder map must follow this format:
     * [
     *     SomeAction::class => 'responder_service_id'
     * ]
     *
     * @param array $actionToResponderMap A map of class names to service IDs
     *
     * @return void
     *
     * @throws LogicException When an action class is not valid
     */
    public function registerResponders(array $actionToResponderMap)
    {
        foreach ($actionToResponderMap as $actionClass => $serviceId) {
            $this->registerResponder($actionClass, $serviceId);
        }
    }

    /**
     * Registers a responder
     *
     * @param string $actionClass The full action class name
     * @param string $serviceId   The responder service ID
     *
     * @return void
     *
     * @throws LogicException When the action class is not valid
     */
    public function registerResponder($actionClass, $serviceId)
    {
        if (!Test::isSubclassOf($actionClass, Action::class)) {
            $message = sprintf('Invalid action class: %s', $actionClass);
            throw new LogicException($message);
        }

        $type = Type::create($actionClass)->toString();

        $this->responders[$type] = (string) $serviceId;
    }

    /**
     * Retrieves a responder by action class name
     *
     * @param string $actionClass The full action class name
     *
     * @return Responder
     *
     * @throws LogicException When the handler is not found
     */
    public function getResponder($actionClass)
    {
        $type = Type::create($actionClass)->toString();

        if (!isset($this->responders[$type])) {
            $message = sprintf('Responder not defined for action: %s', $actionClass);
            throw new LogicException($message);
        }

        $serviceId = $this->responders[$type];

        try {
            $responder = $this->container->get($serviceId);
        } catch (ServiceContainerException $exception) {
            throw new LogicException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $responder;
    }

    /**
     * Checks if a responder is defined for an action
     *
     * @param string $actionClass The full action class name
     *
     * @return bool
     */
    public function hasResponder($actionClass)
    {
        $type = Type::create($actionClass)->toString();

        if (!isset($this->responders[$type])) {
            return false;
        }

        $serviceId = $this->responders[$type];

        return $this->container->has($serviceId);
    }
}
