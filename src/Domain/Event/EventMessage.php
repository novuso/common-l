<?php

namespace Novuso\Common\Domain\Event;

use Novuso\Common\Domain\Event\Api\DomainEvent;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Serialization\Serializable;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Contract;
use Novuso\System\Type\Equatable;
use Novuso\System\Utility\Test;

/**
 * EventMessage is a message wrapper for a domain event
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EventMessage implements Comparable, Equatable, Serializable
{
    /**
     * Event ID
     *
     * @var EventId
     */
    protected $eventId;

    /**
     * Event type
     *
     * @var Contract
     */
    protected $eventType;

    /**
     * Aggregate ID
     *
     * @var Identifier
     */
    protected $aggregateId;

    /**
     * Aggregate Type
     *
     * @var Contract
     */
    protected $aggregateType;

    /**
     * Timestamp
     *
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Meta data
     *
     * @var MetaData
     */
    protected $metaData;

    /**
     * Domain event data
     *
     * @var DomainEvent
     */
    protected $domainEvent;

    /**
     * Sequence number
     *
     * @var int
     */
    protected $sequenceNumber;

    /**
     * Constructs EventMessage
     *
     * @param EventId     $eventId       The event ID
     * @param Identifier  $aggregateId   The aggregate ID
     * @param Contract    $aggregateType The aggregate type
     * @param DateTime    $dateTime      The timestamp
     * @param MetaData    $metaData      The meta data
     * @param DomainEvent $domainEvent   The domain event
     * @param int         $sequence      The sequence number
     */
    public function __construct(
        EventId $eventId,
        Identifier $aggregateId,
        Contract $aggregateType,
        DateTime $dateTime,
        MetaData $metaData,
        DomainEvent $domainEvent,
        $sequence = 0
    ) {
        $this->eventId = $eventId;
        $this->eventType = Contract::create($domainEvent);
        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->dateTime = $dateTime;
        $this->metaData = $metaData;
        $this->domainEvent = $domainEvent;
        $this->sequenceNumber = (int) $sequence;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $sequence = $data['sequence'];
        $eventId = EventId::fromString($data['eventId']);
        $aggIdClass = Contract::create($data['identifierType'])->toClassName();
        $aggregateId = $aggIdClass::fromString($data['identifier']);
        $aggregateType = Contract::create($data['aggregateType']);
        $dateTime = DateTime::fromString($data['dateTime']);
        $metaData = MetaData::deserialize($data['metaData']);
        $eventClass = Contract::create($data['eventType'])->toClassName();
        $domainEvent = $eventClass::deserialize($data['domainEvent']);

        return new self($eventId, $aggregateId, $aggregateType, $dateTime, $metaData, $domainEvent, $sequence);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $aggregateIdType = Contract::create($this->aggregateId);

        return [
            'sequence'       => $this->sequenceNumber,
            'eventId'        => $this->eventId->toString(),
            'eventType'      => $this->eventType->toString(),
            'identifier'     => $this->aggregateId->toString(),
            'identifierType' => $aggregateIdType->toString(),
            'aggregateType'  => $this->aggregateType->toString(),
            'dateTime'       => $this->dateTime->toString(),
            'metaData'       => $this->metaData->serialize(),
            'domainEvent'    => $this->domainEvent->serialize()
        ];
    }

    /**
     * Retrieves a unique identifier
     *
     * @return Identifier
     */
    public function eventId()
    {
        return $this->eventId;
    }

    /**
     * Retrieves the sequence number
     *
     * @return int
     */
    public function sequenceNumber()
    {
        return $this->sequenceNumber;
    }

    /**
     * Retrieves the event type
     *
     * @return Contract
     */
    public function eventType()
    {
        return $this->eventType;
    }

    /**
     * Retrieves the aggregate ID
     *
     * @return Identifier
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * Retrieves the aggregate contract
     *
     * @return Contract
     */
    public function aggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * Retrieves the timestamp
     *
     * @return DateTime
     */
    public function dateTime()
    {
        return $this->dateTime;
    }

    /**
     * Retrieves the meta data
     *
     * @return MetaData
     */
    public function metaData()
    {
        return $this->metaData;
    }

    /**
     * Retrieves the domain event
     *
     * @return DomainEvent
     */
    public function domainEvent()
    {
        return $this->domainEvent;
    }

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString()
    {
        return json_encode($this->serialize(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
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
        assert(
            Test::areEqual($this->aggregateId, $object->aggregateId),
            'Comparison must be for a single aggregate'
        );

        $thisSeq = $this->sequenceNumber;
        $thatSeq = $object->sequenceNumber;

        if ($thisSeq > $thatSeq) {
            return 1;
        }
        if ($thisSeq < $thatSeq) {
            return -1;
        }

        return 0;
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

        return $this->eventId->equals($object->eventId);
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue()
    {
        return $this->eventId->hashValue();
    }
}
