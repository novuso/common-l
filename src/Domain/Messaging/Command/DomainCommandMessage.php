<?php

namespace Novuso\Common\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\Common\Domain\Model\Identifier;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * DomainCommandMessage is the message wrapper for a command
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class DomainCommandMessage implements CommandMessage
{
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
     * @var Command
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

        $message = new self(
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
            'message_id'   => $this->messageId,
            'timestamp'    => $this->timestamp,
            'command_type' => $this->payloadType,
            'command_data' => $this->payload,
            'meta_data'    => $this->metaData
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            'message_id'   => $this->messageId,
            'timestamp'    => $this->timestamp,
            'command_data' => $this->payload,
            'meta_data'    => $this->metaData
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $messageId = $data['message_id'];
        $timestamp = $data['timestamp'];
        $payload = $data['command_data'];
        $metaData = $data['meta_data'];
        $this->__construct($messageId, $timestamp, $payload, $metaData);
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
