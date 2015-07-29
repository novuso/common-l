<?php

namespace Novuso\Common\Domain\Entity;

use Novuso\Common\Domain\Event\DomainEvent;
use Novuso\Common\Domain\Event\EventStream;
use Novuso\System\Exception\OperationException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * AggregateRoot is the base class for an aggregate root
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class AggregateRoot implements RootEntity
{
    /**
     * Event collection
     *
     * @var EventCollection|null
     */
    protected $eventCollection;

    /**
     * Committed version
     *
     * @var int|null
     */
    protected $committedVersion;

    /**
     * {@inheritdoc}
     */
    public function committedVersion()
    {
        if ($this->committedVersion === null) {
            $this->committedVersion = $this->eventCollection()->committedSequence();
        }

        return $this->committedVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecordedEvents()
    {
        $collection = $this->eventCollection();

        return $collection->stream();
    }

    /**
     * {@inheritdoc}
     */
    public function hasRecordedEvents()
    {
        return !($this->eventCollection()->isEmpty());
    }

    /**
     * {@inheritdoc}
     */
    public function commitRecordedEvents()
    {
        $collection = $this->eventCollection();
        $collection->commit();
        $this->committedVersion = $collection->committedSequence();
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object)
    {
        if ($this === $object) {
            return 0;
        }

        assert(
            Test::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

        return $this->id()->compareTo($object->id());
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object)
    {
        if ($this === $object) {
            return true;
        }

        if (!Test::areSameType($this, $object)) {
            return false;
        }

        return $this->id()->equals($object->id());
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue()
    {
        return $this->id()->hashValue();
    }

    /**
     * Records a domain event
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     */
    protected function recordEvent(DomainEvent $domainEvent)
    {
        $this->eventCollection()->record($domainEvent);
    }

    /**
     * Initializes the committed version
     *
     * @param int $committedVersion The initial version
     *
     * @return void
     *
     * @throws OperationException When called with recorded events
     */
    protected function initializeCommittedVersion($committedVersion)
    {
        $eventCollection = $this->eventCollection();

        if (!$eventCollection->isEmpty()) {
            $message = 'Cannot initialize version after recording events';
            throw OperationException::create($message);
        }

        $eventCollection->initializeSequence($committedVersion);
    }

    /**
     * Retrieves the event collection
     *
     * @return EventCollection
     */
    protected function eventCollection()
    {
        if ($this->eventCollection === null) {
            $this->eventCollection = new EventCollection($this->id(), Type::create($this));
        }

        return $this->eventCollection;
    }
}
