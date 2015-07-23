<?php

namespace Novuso\Common\Adapter\Presentation\Resolver;

use LogicException;
use Novuso\Common\Adapter\Presentation\Action;
use Novuso\Common\Adapter\Presentation\Responder;
use Novuso\Common\Application\Service\Container;
use Novuso\Common\Application\Service\Exception\ServiceException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * ResponderServiceResolver resolves an action type to a responder
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ResponderServiceResolver implements ResponderResolver
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
    protected $map = [];

    /**
     * Constructs ResponderServiceResolver
     *
     * @param Container $container            The service container
     * @param array     $actionToResponderMap A map of class names to service IDs
     */
    public function __construct(Container $container, array $actionToResponderMap = [])
    {
        $this->container = $container;
        $this->addResponders($actionToResponderMap);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Type $actionType)
    {
        $type = $actionType->toString();

        if (!isset($this->responders[$type])) {
            $message = sprintf('Responder not defined for action: %s', $actionType->toClassName());
            throw new LogicException($message);
        }

        $serviceId = $this->responders[$type];

        try {
            $responder = $this->container->get($serviceId);
        } catch (ServiceException $exception) {
            throw new LogicException($exception->getMessage(), $exception);
        }

        return $responder;
    }

    /**
     * Registers view responders
     *
     * The action to responder map must follow this format:
     * [
     *     SomeAction::class => "responder_service_id"
     * ]
     *
     * @param array $actionToResponderMap A map of class names to service IDs
     *
     * @return void
     *
     * @throws LogicException When an action class is not valid
     */
    public function addResponders(array $actionToResponderMap)
    {
        foreach ($actionToResponderMap as $actionClass => $serviceId) {
            $this->setResponder($actionClass, $serviceId);
        }
    }

    /**
     * Registers a responder
     *
     * @param string $actionClass The fully-qualified action class name
     * @param string $serviceId   The service ID for the responder
     *
     * @return void
     *
     * @throws LogicException When an action class is not valid
     */
    public function setResponder($actionClass, $serviceId)
    {
        if (!Test::implementsInterface($actionClass, Action::class)) {
            $message = sprintf('Invalid action class: %s', $actionClass);
            throw new LogicException($message);
        }

        $type = Type::create($actionClass)->toString();

        $this->responders[$type] = $serviceId;
    }
}
