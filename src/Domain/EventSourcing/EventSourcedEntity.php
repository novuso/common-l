<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\Exception\RegisterAggregateException;
use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Model\Entity;

/**
 * EventSourcedEntity is the interface for an event sourced entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface EventSourcedEntity extends Entity
{
    /**
     * Registers the aggregate root
     *
     * @internal
     *
     * @param EventSourcedAggregateRoot $aggregateRoot The aggregate root
     *
     * @return void
     *
     * @throws RegisterAggregateException When the registration is invalid
     */
    public function registerAggregateRoot(EventSourcedAggregateRoot $aggregateRoot);

    /**
     * Handles a domain event recursively
     *
     * @internal
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     *
     * @throws RegisterAggregateException When the aggregate root is invalid
     */
    public function handleRecursively(DomainEvent $domainEvent);
}
