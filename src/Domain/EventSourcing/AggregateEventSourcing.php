<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Messaging\Event\EventStream;
use Novuso\Common\Domain\Model\EventRecords;
use Novuso\System\Utility\ClassName;
use Traversable;

/**
 * AggregateEventSourcing provides methods for handling aggregate events
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
trait AggregateEventSourcing
{
    use EventRecords;

    /**
     * Records a domain event
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
    public function recordThat(DomainEvent $domainEvent)
    {
        $this->eventCollection()->record($domainEvent);
        $this->handleRecursively($domainEvent);
    }

    /**
     * Initialize state from an event stream history
     *
     * @param EventStream $eventStream The event stream
     *
     * @return void
     */
    protected function initializeFromEventStream(EventStream $eventStream)
    {
        $lastSequence = null;

        foreach ($eventStream as $eventMessage) {
            $lastSequence = $eventMessage->sequence();
            $this->handleRecursively($eventMessage->payload());
        }

        $this->initializeCommittedVersion($lastSequence);
    }

    /**
     * Handles domain event recursively
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     */
    protected function handleRecursively(DomainEvent $domainEvent)
    {
        $this->handle($domainEvent);

        $childEntities = $this->childEntities();
        if ($childEntities !== null) {
            foreach ($childEntities as $entity) {
                $entity->internalRegisterAggregateRoot($this);
                $entity->internalHandleRecursively($domainEvent);
            }
        }
    }

    /**
     * Handles event if the apply method is available
     *
     * This method delegates to a protected method based on the domain event
     * class name: 'apply'.$className
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     */
    protected function handle(DomainEvent $domainEvent)
    {
        $method = 'apply'.ClassName::short($domainEvent);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($domainEvent);
    }

    /**
     * Retrieves a list of child entities
     *
     * Returns an empty list or null if there are no child entities.
     *
     * @return array|Traversable|null
     */
    protected function childEntities()
    {
        return null;
    }
}
