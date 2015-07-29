<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\Entity\AggregateRoot;
use Novuso\Common\Domain\Event\DomainEvent;
use Novuso\Common\Domain\Event\EventStream;
use Novuso\System\Utility\ClassName;
use ReflectionClass;
use Traversable;

/**
 * EventSourcedAggregateRoot is the base class for an event sourced aggregate
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class EventSourcedAggregateRoot extends AggregateRoot
{
    /**
     * Creates instance from an event stream history
     *
     * Override to customize instantiation without needing reflection.
     *
     * @param EventStream $eventStream The event stream
     *
     * @return EventSourcedAggregateRoot
     */
    public static function reconstitute(EventStream $eventStream)
    {
        $reflection = new ReflectionClass(static::class);
        $aggregate = $reflection->newInstanceWithoutConstructor();

        $lastSequence = null;
        foreach ($eventStream as $eventMessage) {
            $lastSequence = $eventMessage->sequence();
            $aggregate->handleRecursively($eventMessage->eventData());
        }

        $aggregate->initializeCommittedVersion($lastSequence);

        return $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    protected function recordEvent(DomainEvent $domainEvent)
    {
        parent::recordEvent($domainEvent);
        $this->handleRecursively($domainEvent);
    }

    /**
     * Handles event recursively
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
                $entity->registerAggregateRoot($this);
                $entity->handleRecursively($domainEvent);
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
