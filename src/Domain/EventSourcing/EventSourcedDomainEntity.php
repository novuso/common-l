<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\Exception\RegisterAggregateException;
use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Model\DomainEntity;
use Novuso\System\Utility\ClassName;
use Traversable;

/**
 * EventSourcedDomainEntity is the base class for an event sourced entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
abstract class EventSourcedDomainEntity extends DomainEntity implements EventSourcedEntity
{
    /**
     * Aggregate Root
     *
     * @var EventSourcedAggregateRoot
     */
    protected $aggregateRoot;

    /**
     * {@inheritdoc}
     */
    public function registerAggregateRoot(EventSourcedAggregateRoot $aggregateRoot)
    {
        // @codeCoverageIgnoreStart
        if ($this->aggregateRoot !== null && $this->aggregateRoot !== $aggregateRoot) {
            throw RegisterAggregateException::create('Aggregate root already registered');
        }
        // @codeCoverageIgnoreEnd

        $this->aggregateRoot = $aggregateRoot;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRecursively(DomainEvent $domainEvent)
    {
        $this->handle($domainEvent);

        $childEntities = $this->childEntities();
        // @codeCoverageIgnoreStart
        if ($childEntities !== null) {
            foreach ($childEntities as $entity) {
                $entity->registerAggregateRoot($this->aggregateRoot);
                $entity->handleRecursively($domainEvent);
            }
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Retrieves the aggregate root
     *
     * @return EventSourcedAggregateRoot
     *
     * @throws RegisterAggregateException When aggregate root is not registered
     */
    protected function getAggregateRoot()
    {
        // @codeCoverageIgnoreStart
        if ($this->aggregateRoot === null) {
            throw RegisterAggregateException::create('Aggregate root is not registered');
        }
        // @codeCoverageIgnoreEnd

        return $this->aggregateRoot;
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
