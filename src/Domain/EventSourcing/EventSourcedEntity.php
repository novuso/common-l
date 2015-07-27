<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\Entity\Entity;
use Novuso\Common\Domain\Event\DomainEvent;
use Novuso\Common\Domain\EventSourcing\Exception\RegisterAggregateException;

/**
 * EventSourcedEntity is the interface for an event sourced entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface EventSourcedEntity extends Entity
{
    /**
     * Registers the aggregate root
     *
     * @param EventSourcedAggregateRoot $aggregateRoot The aggregate root
     *
     * @return void
     *
     * @throws RegisterAggregateException When the registration is invalid
     */
    public function registerAggregateRoot(EventSourcedAggregateRoot $aggregateRoot);

    /**
     * Handles event recursively
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     */
    public function handleRecursively(DomainEvent $domainEvent);
}
