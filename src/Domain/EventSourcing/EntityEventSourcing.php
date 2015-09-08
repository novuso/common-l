<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\Api\EventSourcedRootEntity;
use Novuso\Common\Domain\EventSourcing\Exception\RegisterAggregateException;
use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\System\Utility\ClassName;
use Traversable;

/**
 * EntityEventSourcing provides methods for handling entity events
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
trait EntityEventSourcing
{
    /**
     * Aggregate root
     *
     * @var EventSourcedRootEntity
     */
    protected $aggregateRoot;

    /**
     * Registers the aggregate root
     *
     * @internal
     *
     * @param EventSourcedRootEntity $aggregateRoot The aggregate root
     *
     * @return void
     *
     * @throws RegisterAggregateException When the registration is invalid
     */
    public function internalRegisterAggregateRoot(EventSourcedRootEntity $aggregateRoot)
    {
        if ($this->aggregateRoot !== null && $this->aggregateRoot !== $aggregateRoot) {
            throw RegisterAggregateException::create('Aggregate root already registered');
        }

        $this->aggregateRoot = $aggregateRoot;
    }

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
    public function internalHandleRecursively(DomainEvent $domainEvent)
    {
        $this->handle($domainEvent);

        $childEntities = $this->childEntities();
        if ($childEntities !== null) {
            foreach ($childEntities as $entity) {
                $entity->internalRegisterAggregateRoot($this->getAggregateRoot());
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

    /**
     * Retrieves the aggregate root
     *
     * @return EventSourcedAggregateRoot
     *
     * @throws RegisterAggregateException When the aggregate root is invalid
     */
    protected function getAggregateRoot()
    {
        if ($this->aggregateRoot === null) {
            throw RegisterAggregateException::create('Aggregate root is not registered');
        }

        return $this->aggregateRoot;
    }
}
