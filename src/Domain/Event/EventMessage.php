<?php

namespace Novuso\Common\Domain\Event;

use Novuso\Common\Domain\Identifier\Identifier;
use Novuso\Common\Domain\Value\DateTime\DateTime;
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
     * Object ID
     *
     * @var Identifier
     */
    protected $objectId;

    /**
     * Object ID type
     *
     * @var Type
     */
    protected $objectIdType;

    /**
     * Object Type
     *
     * @var Type
     */
    protected $objectType;

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
    protected $eventData;

    /**
     * Sequence number
     *
     * @var int
     */
    protected $sequence;

    /**
     * Constructs EventMessage
     *
     * @param EventId     $eventId    The event ID
     * @param Identifier  $objectId   The associated ID
     * @param Type        $objectType The associated type
     * @param DateTime    $dateTime   The timestamp
     * @param MetaData    $metaData   The meta data
     * @param DomainEvent $eventData  The domain event
     * @param int         $sequence   The sequence number
     */
    public function __construct(
        EventId $eventId,
        Identifier $objectId,
        Type $objectType,
        DateTime $dateTime,
        MetaData $metaData,
        DomainEvent $eventData,
        $sequence = 0
    ) {
        $this->objectId = $objectId;
        $this->objectIdType = Type::create($objectId);
        $this->objectType = $objectType;
        $this->eventId = $eventId;
        $this->eventType = Type::create($eventData);
        $this->dateTime = $dateTime;
        $this->metaData = $metaData;
        $this->eventData = $eventData;
        $this->sequence = (int) $sequence;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $objectIdClass = Type::create($data['objectId']['type'])->toClassName();
        $objectId = $objectIdClass::fromString($data['objectId']['identifier']);
        $objectType = Type::create($data['objectType']);
        $eventId = EventId::fromString($data['eventId']);
        $dateTime = DateTime::fromString($data['dateTime']);
        $metaData = MetaData::deserialize($data['metaData']);
        $eventClass = Type::create($data['eventData']['type'])->toClassName();
        $eventData = $eventClass::deserialize($data['eventData']['data']);
        $sequence = $data['sequence'];

        return new self($eventId, $objectId, $objectType, $dateTime, $metaData, $eventData, $sequence);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return [
            'objectId'   => [
                'type'       => $this->objectIdType->toString(),
                'identifier' => $this->objectId->toString()
            ],
            'objectType' => $this->objectType->toString(),
            'eventId'    => $this->eventId->toString(),
            'dateTime'   => $this->dateTime->toString(),
            'metaData'   => $this->metaData->serialize(),
            'eventData'  => [
                'type' => $this->eventType->toString(),
                'data' => $this->eventData->serialize()
            ],
            'sequence'   => $this->sequence
        ];
    }

    /**
     * Retrieves the object ID
     *
     * @return Identifier
     */
    public function objectId()
    {
        return $this->objectId;
    }

    /**
     * Retrieves the object ID type
     *
     * @return Type
     */
    public function objectIdType()
    {
        return $this->objectIdType;
    }

    /**
     * Retrieves the object type
     *
     * @return Type
     */
    public function objectType()
    {
        return $this->objectType;
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
     * Retrieves the event type
     *
     * @return Type
     */
    public function eventType()
    {
        return $this->eventType;
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
    public function eventData()
    {
        return $this->eventData;
    }

    /**
     * Retrieves the sequence number
     *
     * @return int
     */
    public function sequence()
    {
        return $this->sequence;
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
            Test::areEqual($this->objectId, $object->objectId),
            'Comparison must be for a single object ID'
        );

        $thisSeq = $this->sequence;
        $thatSeq = $object->sequence;

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
