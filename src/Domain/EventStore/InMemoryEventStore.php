<?php

namespace Novuso\Common\Domain\EventStore;

use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\EventStream;
use Novuso\Common\Domain\EventStore\Exception\ConcurrencyException;
use Novuso\Common\Domain\EventStore\Exception\StreamNotFoundException;
use Novuso\Common\Domain\Identifier\Identifier;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\System\Serialization\Serializer;
use Novuso\System\Type\Type;

/**
 * InMemoryEventStore is an in-memory implementation of an event store
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class InMemoryEventStore implements EventStore
{
    /**
     * Stream data
     *
     * @var array
     */
    protected $streamData = [];

    /**
     * Serializer
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * Constructs InMemoryEventStore
     *
     * @param Serializer|null $serializer The serializer
     */
    public function __construct(Serializer $serializer = null)
    {
        $this->serializer = $serializer ?: new JsonSerializer();
    }

    /**
     * {@inheritdoc}
     */
    public function appendStream(EventStream $eventStream)
    {
        $id = $eventStream->objectId()->toString();
        $type = $eventStream->objectType()->toString();

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
            $events[] = new StoredEvent($eventMessage, $this->serializer);
        }

        $this->streamData[$type][$id]->addEvents($events);
        $this->streamData[$type][$id]->setVersion($eventStream->version());
    }

    /**
     * {@inheritdoc}
     */
    public function append(EventMessage $eventMessage)
    {
        $id = $eventMessage->objectId()->toString();
        $type = $eventMessage->objectType()->toString();

        if (!isset($this->streamData[$type])) {
            $this->streamData[$type] = [];
        }

        if (!isset($this->streamData[$type][$id])) {
            $this->streamData[$type][$id] = new StreamData($id, $type);
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

        $event = new StoredEvent($eventMessage, $this->serializer);

        $this->streamData[$type][$id]->addEvent($event);
        $this->streamData[$type][$id]->setVersion($version);
    }

    /**
     * {@inheritdoc}
     */
    public function load(Identifier $objectId, Type $objectType)
    {
        $id = $objectId->toString();
        $type = $objectType->toString();

        if (!isset($this->streamData[$type])) {
            $message = sprintf('Stream not found for type: %s', $type);
            throw StreamNotFoundException::create($message);
        }
        if (!isset($this->streamData[$type][$id])) {
            $message = sprintf('Stream not found for ID: %s [%s]', $id, $type);
            throw StreamNotFoundException::create($message);
        }

        $streamData = $this->streamData[$type][$id];
        $version = $streamData->getVersion();

        $messages = [];
        foreach ($streamData->getEvents() as $storedEvent) {
            $messages[] = $storedEvent->toEventMessage();
        }

        return new EventStream($objectId, $objectType, $version, $version, $messages);
    }
}
