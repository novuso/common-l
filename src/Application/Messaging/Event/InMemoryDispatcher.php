<?php

namespace Novuso\Common\Application\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\Subscriber;
use Novuso\System\Utility\ClassName;

/**
 * InMemoryDispatcher dispatches events to in-memory handlers
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class InMemoryDispatcher implements Dispatcher
{
    /**
     * Event handlers
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Sorted handlers
     *
     * @var array
     */
    protected $sorted = [];

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
     * {@inheritdoc}
     */
    public function dispatch(EventMessage $message)
    {
        $eventType = ClassName::underscore((string) $message->payloadType());

        foreach ($this->getHandlers($eventType) as $handler) {
            call_user_func($handler, $message);
        }

        foreach ($this->getHandlers(Subscriber::ALL_EVENTS) as $handler) {
            call_user_func($handler, $message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attach(Subscriber $subscriber)
    {
        foreach ($subscriber->eventRegistration() as $eventType => $params) {
            if (is_string($params)) {
                $this->addHandler($eventType, [$subscriber, $params]);
            } elseif (is_string($params[0])) {
                $priority = isset($params[1]) ? (int) $params[1] : 0;
                $this->addHandler($eventType, [$subscriber, $params[0]], $priority);
            } else {
                foreach ($params as $handler) {
                    $priority = isset($handler[1]) ? (int) $handler[1] : 0;
                    $this->addHandler($eventType, [$subscriber, $handler[0]], $priority);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function detach(Subscriber $subscriber)
    {
        foreach ($subscriber->eventRegistration() as $eventType => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $handler) {
                    $this->removeHandler($eventType, [$subscriber, $handler[0]]);
                }
            } else {
                $handler = is_string($params) ? $params : $params[0];
                $this->removeHandler($eventType, [$subscriber, $handler]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler($eventType, callable $handler, $priority = 0)
    {
        $eventType = (string) $eventType;
        $priority = (int) $priority;

        if (!isset($this->handlers[$eventType])) {
            $this->handlers[$eventType] = [];
        }

        if (!isset($this->handlers[$eventType][$priority])) {
            $this->handlers[$eventType][$priority] = [];
        }

        $this->handlers[$eventType][$priority][] = $handler;
        unset($this->sorted[$eventType]);
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlers($eventType = null)
    {
        if ($eventType !== null) {
            $eventType = (string) $eventType;

            if (!isset($this->handlers[$eventType])) {
                return [];
            }

            if (!isset($this->sorted[$eventType])) {
                $this->sortHandlers($eventType);
            }

            return $this->sorted[$eventType];
        }

        foreach (array_keys($this->handlers) as $eventType) {
            if (!isset($this->sorted[$eventType])) {
                $this->sortHandlers($eventType);
            }
        }

        return array_filter($this->sorted);
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandlers($eventType = null)
    {
        return (bool) count($this->getHandlers($eventType));
    }

    /**
     * {@inheritdoc}
     */
    public function removeHandler($eventType, callable $handler)
    {
        $eventType = (string) $eventType;

        if (!isset($this->handlers[$eventType])) {
            return;
        }

        foreach ($this->handlers[$eventType] as $priority => $handlers) {
            $key = array_search($handler, $handlers, true);
            if ($key !== false) {
                unset($this->handlers[$eventType][$priority][$key]);
                unset($this->sorted[$eventType]);
                if (empty($this->handlers[$eventType][$priority])) {
                    unset($this->handlers[$eventType][$priority]);
                }
                if (empty($this->handlers[$eventType])) {
                    unset($this->handlers[$eventType]);
                }
            }
        }
    }

    /**
     * Sorts event handlers by priority
     *
     * @param string $eventType The event type
     *
     * @return void
     */
    private function sortHandlers($eventType)
    {
        $this->sorted[$eventType] = [];
        if (isset($this->handlers[$eventType])) {
            krsort($this->handlers[$eventType]);
            $this->sorted[$eventType] = call_user_func_array(
                'array_merge',
                $this->handlers[$eventType]
            );
        }
    }
}
