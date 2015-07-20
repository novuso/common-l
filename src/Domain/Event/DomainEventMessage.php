<?php

namespace Novuso\Common\Domain\Event;

use Novuso\Common\Domain\Event\Api\Event;
use Novuso\Common\Domain\Event\Api\EventMessage;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\Contract;
use Novuso\System\Utility\Test;

/**
 * DomainEventMessage is a message wrapper for a domain event
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class DomainEventMessage implements EventMessage
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
     * @var Event
     */
    protected $eventData;

    /**
     * Sequence number
     *
     * @var int
     */
    protected $sequenceNumber;

    /**
     * Constructs DomainEventMessage
     *
     * @param EventId    $eventId       The event ID
     * @param Identifier $aggregateId   The aggregate ID
     * @param Contract   $aggregateType The aggregate type
     * @param DateTime   $dateTime      The timestamp
     * @param MetaData   $metaData      The meta data
     * @param Event      $eventData     The event data
     * @param int        $sequence      The sequence number
     */
    public function __construct(
        EventId $eventId,
        Identifier $aggregateId,
        Contract $aggregateType,
        DateTime $dateTime,
        MetaData $metaData,
        Event $eventData,
        $sequence = 0
    ) {
        $this->eventId = $eventId;
        $this->eventType = Contract::create($eventData);
        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->dateTime = $dateTime;
        $this->metaData = $metaData;
        $this->eventData = $eventData;
        $this->sequenceNumber = (int) $sequence;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $sequence = $data['sequence'];
        $eventId = EventId::fromString($data['eventId']);
        $aggIdClass = Contract::create($data['aggregateIdType'])->toClassName();
        $aggregateId = $aggIdClass::fromString($data['aggregateId']);
        $aggregateType = Contract::create($data['aggregateType']);
        $dateTime = DateTime::fromString($data['dateTime']);
        $metaData = MetaData::deserialize($data['metaData']);
        $eventClass = Contract::create($data['eventType'])->toClassName();
        $eventData = $eventClass::deserialize($data['eventData']);

        return new self($eventId, $aggregateId, $aggregateType, $dateTime, $metaData, $eventData, $sequence);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $aggregateIdType = Contract::create($this->aggregateId);

        return [
            'sequence'        => $this->sequenceNumber,
            'eventId'         => $this->eventId->toString(),
            'eventType'       => $this->eventType->toString(),
            'aggregateId'     => $this->aggregateId->toString(),
            'aggregateType'   => $this->aggregateType->toString(),
            'aggregateIdType' => $aggregateIdType->toString(),
            'dateTime'        => $this->dateTime->toString(),
            'metaData'        => $this->metaData->serialize(),
            'eventData'       => $this->eventData->serialize()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function eventId()
    {
        return $this->eventId;
    }

    /**
     * {@inheritdoc}
     */
    public function sequenceNumber()
    {
        return $this->sequenceNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function eventType()
    {
        return $this->eventType;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * {@inheritdoc}
     */
    public function dateTime()
    {
        return $this->dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function metaData()
    {
        return $this->metaData;
    }

    /**
     * {@inheritdoc}
     */
    public function eventData()
    {
        return $this->eventData;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return json_encode($this->serialize(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * {@inheritdoc}
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
