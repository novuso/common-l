<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Event\Api\Event;
use Novuso\Common\Domain\Event\Api\EventStream;
use Novuso\Common\Domain\Event\EventCollection;
use Novuso\Common\Domain\Model\Api\RootEntity;
use Novuso\System\Exception\OperationException;
use Novuso\System\Type\Contract;
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
     * Concurrency version
     *
     * @var int|null
     */
    protected $concurrencyVersion;

    /**
     * {@inheritdoc}
     */
    abstract public function id();

    /**
     * {@inheritdoc}
     */
    public function concurrencyVersion()
    {
        if ($this->concurrencyVersion === null) {
            $this->concurrencyVersion = $this->eventCollection()->committedSequence();
        }

        return $this->concurrencyVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function extractRecordedEvents()
    {
        $eventCollection = $this->eventCollection();
        $eventStream = $eventCollection->eventStream();
        $eventCollection->commitEvents();
        $this->concurrencyVersion = $eventCollection->committedSequence();

        return $eventStream;
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
     * @param Event $eventData The event data
     * @param array $metaData  The meta data
     *
     * @return void
     */
    protected function recordThat(Event $eventData, array $metaData = [])
    {
        $this->eventCollection()->add($eventData, $metaData);
    }

    /**
     * Initializes the concurrency version
     *
     * @param int $concurrencyVersion The initial version
     *
     * @return void
     *
     * @throws OperationException When called with recorded events
     */
    protected function initConcurrencyVersion($concurrencyVersion)
    {
        $eventCollection = $this->eventCollection();

        if (!$eventCollection->isEmpty()) {
            $message = 'Cannot initialize version after recording events';
            throw OperationException::create($message);
        }

        $eventCollection->initializeSequence($concurrencyVersion);
    }

    /**
     * Retrieves the event collection
     *
     * @return EventCollection
     */
    protected function eventCollection()
    {
        if ($this->eventCollection === null) {
            $this->eventCollection = new EventCollection($this->id(), Contract::create($this));
        }

        return $this->eventCollection;
    }
}
