<?php

namespace Novuso\Common\Domain\Event\Dispatcher;

use Novuso\Common\Domain\Event\Api\Dispatcher;
use Novuso\Common\Domain\Event\Api\Subscriber;
use Novuso\Common\Domain\Event\EventMessage;
use Novuso\System\Exception\ImmutableException;

/**
 * ImmutableDispatcher is a read-only dispatcher proxy
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class ImmutableDispatcher implements Dispatcher
{
    /**
     * Event dispatcher
     *
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Constructs ImmutableDispatcher
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
     * @throws ImmutableException When called
     */
    public function attach(Subscriber $subscriber)
    {
        throw ImmutableException::create('Cannot modify immutable dispatcher');
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
     * @throws ImmutableException When called
     */
    public function detach(Subscriber $subscriber)
    {
        throw ImmutableException::create('Cannot modify immutable dispatcher');
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
     * @throws ImmutableException When called
     */
    public function addHandler($eventType, callable $handler, $priority = 0)
    {
        throw ImmutableException::create('Cannot modify immutable dispatcher');
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
     * @throws ImmutableException When called
     */
    public function removeHandler($eventType, callable $handler)
    {
        throw ImmutableException::create('Cannot modify immutable dispatcher');
    }
}
