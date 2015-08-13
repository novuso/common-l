<?php

namespace Novuso\Common\Domain\EventStore;

use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\Type;

/**
 * StoredEvent represents a persisted domain event message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class StoredEvent
{
    /**
     * Aggregate ID
     *
     * @var string
     */
    protected $aggregateId;

    /**
     * Aggregate ID type
     *
     * @var string
     */
    protected $aggregateIdType;

    /**
     * Aggregate type
     *
     * @var string
     */
    protected $aggregateType;

    /**
     * Message ID
     *
     * @var string
     */
    protected $messageId;

    /**
     * Timestamp
     *
     * @var string
     */
    protected $timestamp;

    /**
     * Payload
     *
     * @var string
     */
    protected $payload;

    /**
     * Payload type
     *
     * @var string
     */
    protected $payloadType;

    /**
     * Meta data
     *
     * @var string
     */
    protected $metaData;

    /**
     * Sequence
     *
     * @var int
     */
    protected $sequence;

    /**
     * Constructs StoredEvent
     *
     * @param EventMessage $message The event message
     */
    public function __construct(EventMessage $message)
    {
        $this->aggregateId = $message->aggregateId()->toString();
        $this->aggregateIdType = Type::create($message->aggregateId())->toString();
        $this->aggregateType = $message->aggregateType()->toString();
        $this->messageId = $message->messageId()->toString();
        $this->timestamp = $message->timestamp()->toString();
        $this->payload = json_encode($message->payload()->serialize());
        $this->payloadType = $message->payloadType()->toString();
        $this->metaData = json_encode($message->metaData()->serialize());
        $this->sequence = $message->sequence();
    }

    /**
     * Retrieves the aggregate ID
     *
     * @return string
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * Retrieves the aggregate ID type
     *
     * @return string
     */
    public function getAggregateIdType()
    {
        return $this->aggregateIdType;
    }

    /**
     * Retrieves the aggregate type
     *
     * @return string
     */
    public function getAggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * Retrieves the message ID
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Retrieves the timestamp
     *
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Retrieves the payload
     *
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Retrieves the payload type
     *
     * @return string
     */
    public function getPayloadType()
    {
        return $this->payloadType;
    }

    /**
     * Retrieves the meta data
     *
     * @return string
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * Retrieves the sequence
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Converts data to an event message
     *
     * @return EventMessage
     */
    public function toEventMessage()
    {
        $aggregateIdClass = Type::create($this->aggregateIdType)->toClassName();
        $aggregateId = $aggregateIdClass::fromString($this->aggregateId);
        $aggregateType = Type::create($this->aggregateType);
        $messageId = MessageId::fromString($this->messageId);
        $timestamp = DateTime::fromString($this->timestamp);
        $payloadClass = Type::create($this->payloadType)->toClassName();
        $payloadArray = json_decode($this->payload, true);
        $payload = $payloadClass::deserialize($payloadArray);
        $metaDataArray = json_decode($this->metaData, true);
        $metaData = MetaData::deserialize($metaDataArray);
        $sequence = $this->sequence;

        $message = new EventMessage(
            $aggregateId,
            $aggregateType,
            $messageId,
            $timestamp,
            $payload,
            $metaData,
            $sequence
        );

        return $message;
    }
}
