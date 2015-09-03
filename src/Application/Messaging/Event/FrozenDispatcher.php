<?php

namespace Novuso\Common\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\Event\Exception\FrozenDispatcherException;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\Subscriber;

/**
 * FrozenDispatcher is a read-only dispatcher proxy
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class FrozenDispatcher implements Dispatcher
{
    /**
     * Event dispatcher
     *
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Constructs FrozenDispatcher
     *
     * @param Dispatcher $dispatcher The proxied dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(EventMessage $message)
    {
        $this->dispatcher->dispatch($message);
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlers($eventType = null)
    {
        return $this->dispatcher->getHandlers($eventType);
    }

    /**
     * {@inheritdoc}
     */
    public function hasHandlers($eventType = null)
    {
        return $this->dispatcher->hasHandlers($eventType);
    }

    /**
     * Not implemented
     *
     * Not implemented to maintain dispatcher immutability.
     *
     * @param Subscriber $subscriber The event subscriber
     *
     * @return void
     *
     * @throws FrozenDispatcherException When called
     */
    public function attach(Subscriber $subscriber)
    {
        throw FrozenDispatcherException::create('Cannot modify frozen dispatcher');
    }

    /**
     * Not implemented
     *
     * Not implemented to maintain dispatcher immutability.
     *
     * @param Subscriber $subscriber The event subscriber
     *
     * @return void
     *
     * @throws FrozenDispatcherException When called
     */
    public function detach(Subscriber $subscriber)
    {
        throw FrozenDispatcherException::create('Cannot modify frozen dispatcher');
    }

    /**
     * Not implemented
     *
     * Not implemented to maintain dispatcher immutability.
     *
     * @param string   $eventType The event type
     * @param callable $handler   The event handler
     * @param int      $priority  Higher priority handlers are called first
     *
     * @return void
     *
     * @throws FrozenDispatcherException When called
     */
    public function addHandler($eventType, callable $handler, $priority = 0)
    {
        throw FrozenDispatcherException::create('Cannot modify frozen dispatcher');
    }

    /**
     * Not implemented
     *
     * Not implemented to maintain dispatcher immutability.
     *
     * @param string   $eventType The event type
     * @param callable $handler   The event handler
     *
     * @return void
     *
     * @throws FrozenDispatcherException When called
     */
    public function removeHandler($eventType, callable $handler)
    {
        throw FrozenDispatcherException::create('Cannot modify frozen dispatcher');
    }
}
