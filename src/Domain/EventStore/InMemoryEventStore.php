<?php

namespace Novuso\Common\Domain\EventStore;

use Novuso\Common\Domain\EventStore\Exception\ConcurrencyException;
use Novuso\Common\Domain\EventStore\Exception\StreamNotFoundException;
use Novuso\Common\Domain\Messaging\Event\DomainEventStream;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventStream;
use Novuso\Common\Domain\Model\Identifier;
use Novuso\System\Type\Type;

/**
 * InMemoryEventStore is an in-memory implementation of an event store
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class InMemoryEventStore implements EventStore
{
    /**
     * Stream data
     *
     * @var array
     */
    private $streamData = [];

    /**
     * {@inheritdoc}
     */
    public function appendEvent(EventMessage $eventMessage)
    {
        $id = $eventMessage->aggregateId()->toString();
        $type = $eventMessage->aggregateType()->toString();

        if (!isset($this->streamData[$type])) {
            $this->streamData[$type] = [];
        }

        if (!isset($this->streamData[$type][$id])) {
            $this->streamData[$type][$id] = new StreamData();
        }

        $version = $eventMessage->sequence();

        if ($version === 0) {
            $expected = null;
        } else {
            $expected = $version - 1;
        }

        if ($this->streamData[$type][$id]->getVersion() !== $expected) {
            $found = $this->streamData[$type][$id]->getVersion();
            $message = sprintf('Expected v%s; found v%s in stream [%s]{%s}', $expected, $found, $type, $id);
            throw ConcurrencyException::create($message);
        }

        $event = new StoredEvent($eventMessage);

        $this->streamData[$type][$id]->addEvent($event);
        $this->streamData[$type][$id]->setVersion($version);
    }

    /**
     * {@inheritdoc}
     */
    public function appendStream(EventStream $eventStream)
    {
        $id = $eventStream->aggregateId()->toString();
        $type = $eventStream->aggregateType()->toString();

        if (!isset($this->streamData[$type])) {
            $this->streamData[$type] = [];
        }

        if (!isset($this->streamData[$type][$id])) {
            $this->streamData[$type][$id] = new StreamData($id, $type);
        }

        if ($this->streamData[$type][$id]->getVersion() !== $eventStream->committed()) {
            $expected = $eventStream->committed();
            $found = $this->streamData[$type][$id]->getVersion();
            $message = sprintf('Expected v%s; found v%s in stream [%s]{%s}', $expected, $found, $type, $id);
            throw ConcurrencyException::create($message);
        }

        $events = [];
        foreach ($eventStream as $eventMessage) {
            $events[] = new StoredEvent($eventMessage);
        }

        $this->streamData[$type][$id]->addEvents($events);
        $this->streamData[$type][$id]->setVersion($eventStream->version());
    }

    /**
     * {@inheritdoc}
     */
    public function loadStream(Identifier $aggregateId, Type $aggregateType, $first = null, $last = null)
    {
        $id = $aggregateId->toString();
        $type = $aggregateType->toString();

        if (!$this->hasStream($aggregateId, $aggregateType)) {
            $message = sprintf('Stream not found for [%s]{%s}', $type, $id);
            throw StreamNotFoundException::create($message);
        }

        $streamData = $this->streamData[$type][$id];
        $count = count($streamData);
        $first = $this->normalizeFirst($first);
        $last = $this->normalizeLast($last, $count);
        $version = $streamData->getVersion();

        $messages = [];
        foreach ($streamData->getEvents() as $storedEvent) {
            $sequence = $storedEvent->getSequence();
            if ($sequence >= $first && $sequence <= $last) {
                $messages[] = $storedEvent->toEventMessage();
            }
        }

        return new DomainEventStream($aggregateId, $aggregateType, $version, $version, $messages);
    }

    /**
     * {@inheritdoc}
     */
    public function hasStream(Identifier $aggregateId, Type $aggregateType)
    {
        $id = $aggregateId->toString();
        $type = $aggregateType->toString();

        if (!isset($this->streamData[$type])) {
            return false;
        }

        if (!isset($this->streamData[$type][$id])) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves the normalized first version
     *
     * @param int|null $first The first version or null for beginning
     *
     * @return int
     */
    private function normalizeFirst($first)
    {
        if ($first === null) {
            return 0;
        }

        return (int) $first;
    }

    /**
     * Retrieves the normalized last version
     *
     * @param int|null $last  The last version or null for remaining
     * @param int      $count The total event count
     *
     * @return int
     */
    private function normalizeLast($last, $count)
    {
        if ($last === null) {
            return $count - 1;
        }

        return (int) $last;
    }
}
