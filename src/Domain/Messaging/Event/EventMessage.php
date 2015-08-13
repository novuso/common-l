<?php

namespace Novuso\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\Common\Domain\Model\Identifier;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * EventMessage is the message wrapper for a domain event
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class EventMessage implements Message
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
     * @var Type
     */
    protected $aggregateType;

    /**
     * Message ID
     *
     * @var MessageId
     */
    protected $messageId;

    /**
     * Timestamp
     *
     * @var DateTime
     */
    protected $timestamp;

    /**
     * Payload
     *
     * @var DomainEvent
     */
    protected $payload;

    /**
     * Payload type
     *
     * @var Type
     */
    protected $payloadType;

    /**
     * Meta data
     *
     * @var MetaData
     */
    protected $metaData;

    /**
     * Sequence number
     *
     * @var int
     */
    protected $sequence;

    /**
     * Constructs EventMessage
     *
     * @param Identifier  $aggregateId   The aggregate ID
     * @param Type        $aggregateType The aggregate type
     * @param MessageId   $messageId     The message ID
     * @param DateTime    $timestamp     The timestamp
     * @param DomainEvent $payload       The payload
     * @param MetaData    $metaData      The meta data
     * @param int         $sequence      The sequence number
     */
    public function __construct(
        Identifier $aggregateId,
        Type $aggregateType,
        MessageId $messageId,
        DateTime $timestamp,
        DomainEvent $payload,
        MetaData $metaData,
        $sequence
    ) {
        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->messageId = $messageId;
        $this->timestamp = $timestamp;
        $this->payload = $payload;
        $this->payloadType = Type::create($payload);
        $this->metaData = $metaData;
        $this->sequence = (int) $sequence;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $aggregateIdClass = Type::create($data['aggregate_id']['type'])->toClassName();
        $aggregateId = $aggregateIdClass::fromString($data['aggregate_id']['id']);
        $aggregateType = Type::create($data['aggregate_type']);
        $messageId = MessageId::fromString($data['message_id']);
        $timestamp = DateTime::fromString($data['timestamp']);
        $eventClass = Type::create($data['event_type'])->toClassName();
        $payload = $eventClass::deserialize($data['event_data']);
        $metaData = MetaData::deserialize($data['meta_data']);
        $sequence = $data['sequence'];

        return new static($aggregateId, $aggregateType, $messageId, $timestamp, $payload, $metaData, $sequence);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return [
            'message_id'     => $this->messageId->toString(),
            'timestamp'      => $this->timestamp->toString(),
            'event_type'     => $this->payloadType->toString(),
            'event_data'     => $this->payload->serialize(),
            'meta_data'      => $this->metaData->serialize(),
            'aggregate_type' => $this->aggregateType->toString(),
            'aggregate_id'   => [
                'type' => Type::create($this->aggregateId)->toString(),
                'id'   => $this->aggregateId->toString()
            ],
            'sequence'       => $this->sequence
        ];
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
     * Retrieves the aggregate type
     *
     * @return Type
     */
    public function aggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * {@inheritdoc}
     */
    public function messageId()
    {
        return $this->messageId;
    }

    /**
     * {@inheritdoc}
     */
    public function timestamp()
    {
        return $this->timestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function payload()
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     */
    public function payloadType()
    {
        return $this->payloadType;
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
    public function withMetaData(MetaData $metaData)
    {
        $message = new static(
            $this->aggregateId,
            $this->aggregateType,
            $this->messageId,
            $this->timestamp,
            $this->payload,
            $metaData,
            $this->sequence
        );

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function mergeMetaData(MetaData $metaData)
    {
        $meta = clone $this->metaData;
        $meta->merge($metaData);

        $message = new static(
            $this->aggregateId,
            $this->aggregateType,
            $this->messageId,
            $this->timestamp,
            $this->payload,
            $meta,
            $this->sequence
        );

        return $message;
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
     * {@inheritdoc}
     */
    public function toString()
    {
        return json_encode($this->serialize(), JSON_UNESCAPED_SLASHES);
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
            Test::areEqual($this->aggregateType, $object->aggregateType),
            'Comparison must be for a single aggregate type'
        );
        assert(
            Test::areEqual($this->aggregateId, $object->aggregateId),
            'Comparison must be for a single aggregate identifier'
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

        return $this->messageId->equals($object->messageId);
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue()
    {
        return $this->messageId->hashValue();
    }
}
