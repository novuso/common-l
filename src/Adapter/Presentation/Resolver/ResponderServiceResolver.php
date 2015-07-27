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
     * @param Container $container    The service container
     * @param array     $responderMap A map of responder services to actions
     */
    public function __construct(Container $container, array $responderMap = [])
    {
        $this->container = $container;
        $this->addResponders($responderMap);
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
     *     "responder_service_id" => SomeAction::class
     * ]
     *
     * @param array $responderMap A map of responder services to actions
     *
     * @return void
     *
     * @throws LogicException When an action class is not valid
     */
    public function addResponders(array $responderMap)
    {
        foreach ($responderMap as $serviceId => $actionClass) {
            $this->setResponder($serviceId, $actionClass);
        }
    }

    /**
     * Registers a responder
     *
     * @param string $serviceId   The service ID for the responder
     * @param string $actionClass The fully-qualified action class name
     *
     * @return void
     *
     * @throws LogicException When an action class is not valid
     */
    public function setResponder($serviceId, $actionClass)
    {
        if (!Test::implementsInterface($actionClass, Action::class)) {
            $message = sprintf('Invalid action class: %s', $actionClass);
            throw new LogicException($message);
        }

        $type = Type::create($actionClass)->toString();

        $this->responders[$type] = $serviceId;
    }
}
