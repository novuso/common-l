<?php

namespace Novuso\Common\Domain\Event;

/**
 * Dispatcher is the interface for a domain event dispatcher
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Dispatcher
{
    /**
     * Dispatches a domain event message
     *
     * @param EventMessage $message The event message
     *
     * @return void
     */
    public function dispatch(EventMessage $message);

    /**
     * Registers a subscriber to handle events
     *
     * @param Subscriber $subscriber The event subscriber
     *
     * @return void
     */
    public function attach(Subscriber $subscriber);

    /**
     * Unregisters a subscriber from handling events
     *
     * @param Subscriber $subscriber The event subscriber
     *
     * @return void
     */
    public function detach(Subscriber $subscriber);

    /**
     * Adds a handler for a specific event
     *
     * @param string   $eventType The event type
     * @param callable $handler   The event handler
     * @param int      $priority  Higher priority handlers are called first
     *
     * @return void
     */
    public function addHandler($eventType, callable $handler, $priority = 0);

    /**
     * Retrieves handlers for an event or all events
     *
     * @param string|null $eventType The event type; null for all events
     *
     * @return array
     */
    public function getHandlers($eventType = null);

    /**
     * Checks if handlers are registered for an event or any event
     *
     * @param string|null $eventType The event type; null for all events
     *
     * @return bool
     */
    public function hasHandlers($eventType = null);

    /**
     * Removes a handler from a specified event
     *
     * @param string   $eventType The event type
     * @param callable $handler   The event handler
     *
     * @return void
     */
    public function removeHandler($eventType, callable $handler);
}
