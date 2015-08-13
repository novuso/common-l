<?php

namespace Novuso\Common\Adapter\Presentation\Symfony\Http\Resolver;

use LogicException;
use Novuso\Common\Adapter\Presentation\Symfony\Http\Action;
use Novuso\Common\Adapter\Presentation\Symfony\Http\Responder;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * ResponderMemoryMap is an action class to responder instance map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class ResponderMemoryMap
{
    /**
     * Responder map
     *
     * @var array
     */
    protected $responders = [];

    /**
     * Registers HTTP responders
     *
     * The action to responder map must follow this format:
     * [
     *     SomeAction::class => $someResponderInstance
     * ]
     *
     * @param array $actionToResponderMap A map of class names to responders
     *
     * @return void
     *
     * @throws LogicException When an action class is not valid
     */
    public function registerResponders(array $actionToResponderMap)
    {
        foreach ($actionToResponderMap as $actionClass => $responder) {
            $this->registerResponder($actionClass, $responder);
        }
    }

    /**
     * Registers a responder
     *
     * @param string    $actionClass The full action class name
     * @param Responder $responder   The responder
     *
     * @return void
     *
     * @throws LogicException When the action class is not valid
     */
    public function registerResponder($actionClass, Responder $responder)
    {
        if (!Test::implementsInterface($actionClass, Action::class)) {
            $message = sprintf('Invalid action class: %s', $actionClass);
            throw new LogicException($message);
        }

        $type = Type::create($actionClass)->toString();

        $this->responders[$type] = $responder;
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

        return $this->responders[$type];
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

        return isset($this->responders[$type]);
    }
}
