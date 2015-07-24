<?php

namespace Novuso\Common\Domain\Event;

use Countable;
use Novuso\Common\Domain\Identifier\Identifier;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Type\Type;
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
     * Associated ID
     *
     * @var Identifier
     */
    protected $id;

    /**
     * Associated type
     *
     * @var Type
     */
    protected $type;

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
     * @param Identifier $id   The associated ID
     * @param Type       $type The associated type
     */
    public function __construct(Identifier $id, Type $type)
    {
        $this->id = $id;
        $this->type = $type;
        $this->eventMessages = ArrayList::of(EventMessage::class);
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
     * Adds a domain event
     *
     * @param DomainEvent $domainEvent The domain event
     * @param array       $metaData    An associated array of metadata
     *
     * @return void
     */
    public function add(DomainEvent $domainEvent, array $metaData = [])
    {
        $dateTime = DateTime::now();
        $eventId = EventId::generate();
        $metaData = new MetaData($metaData);
        $sequence = $this->nextSequence();

        $eventMessage = new EventMessage(
            $eventId,
            $this->id,
            $this->type,
            $dateTime,
            $metaData,
            $domainEvent,
            $sequence
        );

        $this->lastSequence = $eventMessage->sequence();
        $this->eventMessages->add($eventMessage);
    }

    /**
     * Retrieves an event stream of uncommitted event messages
     *
     * @return EventStream
     */
    public function stream()
    {
        return new EventStream(
            $this->id,
            $this->type,
            $this->committedSequence(),
            $this->lastSequence(),
            $this->eventMessages->toArray()
        );
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
        assert($this->eventMessages->isEmpty(), 'Cannot initialize sequence after adding events');
        $this->committedSequence = (int) $committedSequence;
    }

    /**
     * Retrieves the committed sequence number
     *
     * @return int|null
     */
    public function committedSequence()
    {
        return $this->committedSequence;
    }

    /**
     * Retrieves the last sequence number
     *
     * @return int|null
     */
    public function lastSequence()
    {
        if ($this->eventMessages->isEmpty()) {
            return $this->committedSequence;
        }

        return $this->lastSequence;
    }

    /**
     * Clears events and updates committed sequence number
     *
     * @return void
     */
    public function commit()
    {
        $this->committedSequence = $this->lastSequence();
        $this->eventMessages = ArrayList::of(EventMessage::class);
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
