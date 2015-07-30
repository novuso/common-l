<?php

namespace Novuso\Common\Application\Event;

use Novuso\Common\Application\Container\Container;
use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\InMemoryDispatcher;
use Novuso\System\Utility\ClassName;

/**
 * ServiceAwareDispatcher lazy loads event subscribers from a container
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ServiceAwareDispatcher extends InMemoryDispatcher
{
    /**
     * Service container
     *
     * @var Container
     */
    protected $container;

    /**
     * Service IDs
     *
     * @var array
     */
    protected $serviceIds = [];

    /**
     * Constructs ServiceDispatcher
     *
     * @param Container $container The service container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /*
     * The following methods are derived from code of the Symfony Framework
     * (2.7.1 - 2015-06-11)
     *
     * Copyright (c) 2004-2015 Fabien Potencier. <fabien@symfony.com>
     *
     * Permission is hereby granted, free of charge, to any person obtaining a
     * copy of this software and associated documentation files
     * (the "Software"), to deal in the Software without restriction, including
     * without limitation the rights to use, copy, modify, merge, publish,
     * distribute, sublicense, and/or sell copies of the Software, and to
     * permit persons to whom the Software is furnished to do so, subject to
     * the following conditions:
     *
     * The above copyright notice and this permission notice shall be included
     * in all copies or substantial portions of the Software.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
     * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
     * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
     * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
     * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
     * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
     * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
     */

    /**
     * Registers a subscriber service to handle events
     *
     * The subscriber class must implement:
     * Novuso\Common\Domain\Event\Subscriber
     *
     * @param string $serviceId The subscriber service ID
     * @param string $className The subscriber class name
     *
     * @return void
     */
    public function attachService($serviceId, $className)
    {
        $serviceId = (string) $serviceId;
        $className = (string) $className;

        foreach ($className::eventRegistration() as $eventType => $params) {
            if (is_string($params)) {
                $this->addHandlerService($eventType, $serviceId, $params);
            } elseif (is_string($params[0])) {
                $priority = isset($params[1]) ? (int) $params[1] : 0;
                $this->addHandlerService($eventType, $serviceId, $params[0], $priority);
            } else {
                foreach ($params as $handler) {
                    $priority = isset($handler[1]) ? (int) $handler[1] : 0;
                    $this->addHandlerService($eventType, $serviceId, $handler[0], $priority);
                }
            }
        }
    }

    /**
     * Adds a handler service for a specific event
     *
     * @param string $eventType The event type
     * @param string $serviceId The handler service ID
     * @param string $method    The name of the method to invoke
     * @param int    $priority  Higher priority handlers are called first
     *
     * @return void
     */
    public function addHandlerService($eventType, $serviceId, $method, $priority = 0)
    {
        $eventType = (string) $eventType;
        $serviceId = (string) $serviceId;
        $method = (string) $method;
        $priority = (int) $priority;

        if (!isset($this->serviceIds[$eventType])) {
            $this->serviceIds[$eventType] = [];
        }

        $this->serviceIds[$eventType][] = [$serviceId, $method, $priority];
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(EventMessage $message)
    {
        $this->lazyLoad(ClassName::underscore((string) $message->eventType()));

        return parent::dispatch($message);
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlers($eventType = null)
    {
        if ($eventType === null) {
            foreach (array_keys($this->serviceIds) as $type) {
                $this->lazyLoad($type);
            }
        } else {
            $eventType = (string) $eventType;
            $this->lazyLoad($eventType);
        }

        return parent::getHandlers($eventType);
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandlers($eventType = null)
    {
        if ($eventType === null) {
            return (bool) count($this->serviceIds) || (bool) count($this->handlers);
        }

        $eventType = (string) $eventType;

        if (isset($this->serviceIds[$eventType])) {
            return true;
        }

        return parent::hasHandlers($eventType);
    }

    /**
     * {@inheritdoc}
     */
    public function removeHandler($eventType, callable $handler)
    {
        $eventType = (string) $eventType;

        $this->lazyLoad($eventType);

        if (isset($this->serviceIds[$eventType])) {
            foreach ($this->serviceIds[$eventType] as $i => $args) {
                list($serviceId, $method) = $args;
                $key = $serviceId.'.'.$method;
                if (isset($this->handlers[$eventType][$key])
                    && $handler === [$this->handlers[$eventType][$key], $method]) {
                    unset($this->handlers[$eventType][$key]);
                    if (empty($this->handlers[$eventType])) {
                        unset($this->handlers[$eventType]);
                    }
                    unset($this->serviceIds[$eventType][$i]);
                    if (empty($this->serviceIds[$eventType])) {
                        unset($this->serviceIds[$eventType]);
                    }
                }
            }
        }

        parent::removeHandler($eventType, $handler);
    }

    /**
     * Lazy loads event handlers from the service container
     *
     * @param string $eventType The event type
     *
     * @return void
     */
    protected function lazyLoad($eventType)
    {
        if (isset($this->serviceIds[$eventType])) {
            foreach ($this->serviceIds[$eventType] as $args) {
                list($serviceId, $method, $priority) = $args;
                $service = $this->container->get($serviceId);
                $key = $serviceId.'.'.$method;
                if (!isset($this->handlers[$eventType][$key])) {
                    $this->addHandler($eventType, [$service, $method], $priority);
                } elseif ($service !== $this->handlers[$eventType][$key]) {
                    parent::removeHandler($eventType, [$this->handlers[$eventType][$key], $method]);
                    $this->addHandler($eventType, [$service, $method], $priority);
                }
                $this->handlers[$eventType][$key] = $service;
            }
        }
    }
}
