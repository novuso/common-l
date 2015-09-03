<?php

namespace Novuso\Common\Domain\EventSourcing;

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
     * @var EventSourcedAggregateRoot
     */
    private $aggregateRoot;

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
    public function registerAggregateRoot(EventSourcedAggregateRoot $aggregateRoot)
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
    public function handleRecursively(DomainEvent $domainEvent)
    {
        $this->handle($domainEvent);

        $childEntities = $this->childEntities();
        if ($childEntities !== null) {
            foreach ($childEntities as $entity) {
                $entity->registerAggregateRoot($this->getAggregateRoot());
                $entity->handleRecursively($domainEvent);
            }
        }
    }

    /**
     * Handles event if the apply method is available
     *
     * This method delegates to a private method based on the domain event
     * class name: 'apply'.$className
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     */
    private function handle(DomainEvent $domainEvent)
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
    private function childEntities()
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
    private function getAggregateRoot()
    {
        if ($this->aggregateRoot === null) {
            throw RegisterAggregateException::create('Aggregate root is not registered');
        }

        return $this->aggregateRoot;
    }
}
