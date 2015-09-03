<?php

namespace Novuso\Common\Domain\Model;

use Countable;
use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Messaging\Event\DomainEventMessage;
use Novuso\Common\Domain\Messaging\Event\DomainEventStream;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\EventStream;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Collection\ArrayList;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;
use Serializable;

/**
 * EventCollection is a collection of events for a single aggregate
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EventCollection implements Countable, Serializable
{
    /**
     * Aggregate ID
     *
     * @var Identifier
     */
    private $aggregateId;

    /**
     * Aggregate type
     *
     * @var Type
     */
    private $aggregateType;

    /**
     * Committed sequence number
     *
     * @var int|null
     */
    private $committedSequence;

    /**
     * Last sequence number
     *
     * @var int|null
     */
    private $lastSequence;

    /**
     * Event messages
     *
     * @var ArrayList
     */
    private $messages;

    /**
     * Constructs EventCollection
     *
     * @param Identifier $aggregateId   The aggregate ID
     * @param Type       $aggregateType Tge aggregate type
     */
    public function __construct(Identifier $aggregateId, Type $aggregateType)
    {
        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->messages = ArrayList::of(EventMessage::class);
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->messages->isEmpty();
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return $this->messages->count();
    }

    /**
     * Retrieves an event stream of uncommitted event messages
     *
     * @return EventStream
     */
    public function stream()
    {
        $stream = new DomainEventStream(
            $this->aggregateId,
            $this->aggregateType,
            $this->committedSequence(),
            $this->lastSequence(),
            $this->messages->toArray()
        );

        return $stream;
    }

    /**
     * Records a domain event
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     */
    public function record(DomainEvent $domainEvent)
    {
        $dateTime = DateTime::now();
        $messageId = MessageId::generate();
        $metaData = new MetaData();

        $message = new DomainEventMessage(
            $this->aggregateId,
            $this->aggregateType,
            $messageId,
            $dateTime,
            $domainEvent,
            $metaData,
            $this->nextSequence()
        );

        $this->lastSequence = $message->sequence();
        $this->messages->add($message);
    }

    /**
     * Initializes the sequence
     *
     * @param int $committedSequence The committed sequence number
     *
     * @return void
     */
    public function initializeSequence($committedSequence)
    {
        assert($this->messages->isEmpty(), 'Cannot initialize sequence after adding events');
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
        if ($this->messages->isEmpty()) {
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
        $this->messages = ArrayList::of(EventMessage::class);
    }

    /**
     * Retrieves a serialized representation
     *
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'aggregate_id'       => $this->aggregateId,
            'aggregate_type'     => $this->aggregateType,
            'committed_sequence' => $this->committedSequence,
            'last_sequence'      => $this->lastSequence,
            'messages'           => $this->messages->toArray()
        ]);
    }

    /**
     * Handles construction from a serialized representation
     *
     * @param string $serialized The serialized representation
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->aggregateId = $data['aggregate_id'];
        $this->aggregateType = $data['aggregate_type'];
        $this->committedSequence = $data['committed_sequence'];
        $this->lastSequence = $data['last_sequence'];
        $this->messages = ArrayList::of(EventMessage::class);
        foreach ($data['messages'] as $message) {
            $this->messages->add($message);
        }
    }

    /**
     * Retrieves the next sequence number
     *
     * @return int
     */
    private function nextSequence()
    {
        $sequence = $this->lastSequence();

        if ($sequence === null) {
            return 0;
        }

        return $sequence + 1;
    }
}
