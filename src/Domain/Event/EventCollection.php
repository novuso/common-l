<?php

namespace Novuso\Common\Domain\Event;

use Countable;
use Novuso\Common\Domain\Event\Api\Event;
use Novuso\Common\Domain\Event\Api\EventStream;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\Common\Domain\DateTime\DateTime;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Type\Contract;
use Novuso\System\Utility\Test;

/**
 * EventCollection is a collection of events for a single aggregate
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EventCollection implements Countable
{
    /**
     * Aggregate ID
     *
     * @var Identifier
     */
    protected $aggregateId;

    /**
     * Aggregate type
     *
     * @var Contract
     */
    protected $aggregateType;

    /**
     * Committed sequence number
     *
     * @var int|null
     */
    protected $committedSequence;

    /**
     * Last sequence number
     *
     * @var int|null
     */
    protected $lastSequence;

    /**
     * Event messages
     *
     * @var ArrayList
     */
    protected $eventMessages;

    /**
     * Constructs EventCollection
     *
     * @param Identifier $aggregateId   The aggregate ID
     * @param Contract   $aggregateType The aggregate type
     */
    public function __construct(Identifier $aggregateId, Contract $aggregateType)
    {
        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->eventMessages = ArrayList::of(DomainEventMessage::class);
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->eventMessages->isEmpty();
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return $this->eventMessages->count();
    }

    /**
     * Adds an event
     *
     * @param Event $eventData The event data
     * @param array $metaData  An associated array of metadata
     *
     * @return void
     */
    public function add(Event $eventData, array $metaData = [])
    {
        $dateTime = DateTime::now();
        $eventId = EventId::generate();
        $aggregateId = $this->aggregateId;
        $aggregateType = $this->aggregateType;
        $metaData = new MetaData($metaData);
        $sequence = $this->nextSequence();

        $eventMessage = new DomainEventMessage(
            $eventId,
            $aggregateId,
            $aggregateType,
            $dateTime,
            $metaData,
            $sequence
        );

        $this->lastSequence = $eventMessage->sequenceNumber();
        $this->eventMessages->add($eventMessage);
    }

    /**
     * Retrieves an event stream of uncommitted event messages
     *
     * @return EventStream
     */
    public function eventStream()
    {
        return new DomainEventStream($this->eventMessages->toArray());
    }

    /**
     * Initializes the sequence
     *
     * @param int $committedSequence The commited sequence number
     *
     * @return void
     */
    public function initializeSequence($committedSequence)
    {
        assert($this->eventMessages->isEmpty(), sprintf('%s must be called before events are added', __METHOD__));

        $this->committedSequence = (int) $committedSequence;
    }

    /**
     * Retrieves the committed sequence number
     *
     * @return int
     */
    public function committedSequence()
    {
        return $this->committedSequence;
    }

    /**
     * Retrieves the last sequence number
     *
     * @return int
     */
    public function lastSequence()
    {
        if ($this->eventMessages->isEmpty()) {
            return $this->committedSequence;
        } elseif ($this->lastSequence === null) {
            $last = $this->eventMessages->last();
            $this->lastSequence = $last->sequenceNumber();
        }

        return $this->lastSequence;
    }

    /**
     * Clears events and updates committed sequence number
     *
     * @return void
     */
    public function commitEvents()
    {
        $this->committedSequence = $this->lastSequence();
        $this->eventMessages = ArrayList::of(DomainEventMessage::class);
    }

    /**
     * Retrieves the next sequence number
     *
     * @return int
     */
    private function nextSequence()
    {
        $number = $this->lastSequence();

        if ($number === null) {
            return 0;
        }

        return $number + 1;
    }
}
