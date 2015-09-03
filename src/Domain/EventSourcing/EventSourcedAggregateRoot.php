<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Messaging\Event\EventStream;
use Novuso\Common\Domain\Model\AggregateRoot;

/**
 * EventSourcedAggregateRoot is the interface for an aggregate root
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface EventSourcedAggregateRoot extends AggregateRoot
{
    /**
     * Creates instance from an event stream history
     *
     * @param EventStream $eventStream The event stream
     *
     * @return EventSourcedAggregateRoot
     */
    public static function reconstitute(EventStream $eventStream);

    /**
     * Applies a domain event
     *
     * Calling this method results in the domain event being recorded and
     * state changes applied. Should only be called internally or from a child
     * entity.
     *
     * @internal
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     */
    public function apply(DomainEvent $domainEvent);
}
