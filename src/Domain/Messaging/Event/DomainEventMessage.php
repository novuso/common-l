<?php

namespace Novuso\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * DomainEventMessage is the message wrapper for a domain event
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class DomainEventMessage implements EventMessage
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
     * Message ID
     *
     * @var MessageId
     */
    private $messageId;

    /**
     * Timestamp
     *
     * @var DateTime
     */
    private $timestamp;

    /**
     * Payload
     *
     * @var DomainEvent
     */
    private $payload;

    /**
     * Payload type
     *
     * @var Type
     */
    private $payloadType;

    /**
     * Meta data
     *
     * @var MetaData
     */
    private $metaData;

    /**
     * Sequence number
     *
     * @var int
     */
    private $sequence;

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
        $message = new self(
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

        $message = new self(
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
     * {@inheritdoc}
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
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_SLASHES);
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
    public function jsonSerialize()
    {
        return [
            'message_id'     => $this->messageId,
            'timestamp'      => $this->timestamp,
            'event_type'     => $this->payloadType,
            'event_data'     => $this->payload,
            'meta_data'      => $this->metaData,
            'aggregate_type' => $this->aggregateType,
            'aggregate_id'   => $this->aggregateId,
            'sequence'       => $this->sequence
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            'message_id'     => $this->messageId,
            'timestamp'      => $this->timestamp,
            'event_data'     => $this->payload,
            'meta_data'      => $this->metaData,
            'aggregate_type' => $this->aggregateType,
            'aggregate_id'   => $this->aggregateId,
            'sequence'       => $this->sequence
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $aggregateId = $data['aggregate_id'];
        $aggregateType = $data['aggregate_type'];
        $messageId = $data['message_id'];
        $timestamp = $data['timestamp'];
        $payload = $data['event_data'];
        $metaData = $data['meta_data'];
        $sequence = $data['sequence'];
        $this->__construct(
            $aggregateId,
            $aggregateType,
            $messageId,
            $timestamp,
            $payload,
            $metaData,
            $sequence
        );
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
