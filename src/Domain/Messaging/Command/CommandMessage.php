<?php

namespace Novuso\Common\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\Common\Domain\Model\Identifier;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * CommandMessage is the message wrapper for a command
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class CommandMessage implements Message
{
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
     * @var Command
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
     * Constructs CommandMessage
     *
     * @param MessageId $messageId The message ID
     * @param DateTime  $timestamp The timestamp
     * @param Command   $payload   The payload
     * @param MetaData  $metaData  The meta data
     */
    public function __construct(MessageId $messageId, DateTime $timestamp, Command $payload, MetaData $metaData)
    {
        $this->messageId = $messageId;
        $this->timestamp = $timestamp;
        $this->payload = $payload;
        $this->payloadType = Type::create($payload);
        $this->metaData = $metaData;
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $messageId = MessageId::fromString($data['message_id']);
        $timestamp = DateTime::fromString($data['timestamp']);
        $commandClass = Type::create($data['command_type'])->toClassName();
        $payload = $commandClass::deserialize($data['command_data']);
        $metaData = MetaData::deserialize($data['meta_data']);

        return new static($messageId, $timestamp, $payload, $metaData);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return [
            'message_id'   => $this->messageId->toString(),
            'timestamp'    => $this->timestamp->toString(),
            'command_type' => $this->payloadType->toString(),
            'command_data' => $this->payload->serialize(),
            'meta_data'    => $this->metaData->serialize()
        ];
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
            $this->messageId,
            $this->timestamp,
            $this->payload,
            $metaData
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
            $this->messageId,
            $this->timestamp,
            $this->payload,
            $meta
        );

        return $message;
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

        return $this->timestamp->compareTo($object->timestamp);
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
