<?php

namespace Novuso\Common\Domain\Event;

use Novuso\Common\Domain\Event\Api\DomainEvent;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\Common\Domain\Model\ValueSerializer;
use Novuso\System\Serialization\Serializable;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;
use Novuso\System\Type\Type;
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
     * @var Type
     */
    protected $eventType;

    /**
     * Associated ID
     *
     * @var Identifier
     */
    protected $identifier;

    /**
     * Associated Type
     *
     * @var Type
     */
    protected $objectType;

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
     * @param EventId     $eventId     The event ID
     * @param Identifier  $identifier  The associated ID
     * @param Type        $objectType  The associated type
     * @param DateTime    $dateTime    The timestamp
     * @param MetaData    $metaData    The meta data
     * @param DomainEvent $domainEvent The domain event
     * @param int         $sequence    The sequence number
     */
    public function __construct(
        EventId $eventId,
        Identifier $identifier,
        Type $objectType,
        DateTime $dateTime,
        MetaData $metaData,
        DomainEvent $domainEvent,
        $sequence = 0
    ) {
        $this->eventId = $eventId;
        $this->eventType = Type::create($domainEvent);
        $this->identifier = $identifier;
        $this->objectType = $objectType;
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
        $identifier = ValueSerializer::deserialize($data['identifier']);
        $objectType = Type::create($data['objectType']);
        $dateTime = DateTime::fromString($data['dateTime']);
        $metaData = MetaData::deserialize($data['metaData']);
        $eventClass = Type::create($data['eventType'])->toClassName();
        $domainEvent = $eventClass::deserialize($data['domainEvent']);

        return new self($eventId, $identifier, $objectType, $dateTime, $metaData, $domainEvent, $sequence);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return [
            'sequence'    => $this->sequenceNumber,
            'eventId'     => $this->eventId->toString(),
            'eventType'   => $this->eventType->toString(),
            'identifier'  => ValueSerializer::serialize($this->identifier),
            'objectType'  => $this->objectType->toString(),
            'dateTime'    => $this->dateTime->toString(),
            'metaData'    => $this->metaData->serialize(),
            'domainEvent' => $this->domainEvent->serialize()
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
     * @return Type
     */
    public function eventType()
    {
        return $this->eventType;
    }

    /**
     * Retrieves the associated ID
     *
     * @return Identifier
     */
    public function identifier()
    {
        return $this->identifier;
    }

    /**
     * Retrieves the associated type
     *
     * @return Type
     */
    public function objectType()
    {
        return $this->objectType;
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
            Test::areEqual($this->objectType, $object->objectType),
            'Comparison must be for a single object type'
        );
        assert(
            Test::areEqual($this->identifier, $object->identifier),
            'Comparison must be for a single identifier'
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
