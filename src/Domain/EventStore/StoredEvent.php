<?php

namespace Novuso\Common\Domain\EventStore;

use Novuso\Common\Domain\Event\DomainEvent;
use Novuso\Common\Domain\Event\EventId;
use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\MetaData;
use Novuso\Common\Domain\Identifier\Identifier;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\System\Serialization\Serializer;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

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
     * Object ID
     *
     * @var string
     */
    protected $objectId;

    /**
     * Object ID type
     *
     * @var string
     */
    protected $objectIdType;

    /**
     * Object type
     *
     * @var string
     */
    protected $objectType;

    /**
     * Event ID
     *
     * @var string
     */
    protected $eventId;

    /**
     * Timestamp
     *
     * @var string
     */
    protected $dateTime;

    /**
     * Meta data
     *
     * @var string
     */
    protected $metaData;

    /**
     * Event data
     *
     * @var string
     */
    protected $eventData;

    /**
     * Sequence number
     *
     * @var int
     */
    protected $sequence;

    /**
     * Serializer
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * Constructs StoredEvent
     *
     * @param EventMessage    $message    The event message
     * @param Serializer|null $serializer The serializer
     */
    public function __construct(EventMessage $message, Serializer $serializer = null)
    {
        $this->serializer = $serializer ?: new JsonSerializer();
        $this->objectId = $message->objectId()->toString();
        $this->objectIdType = $message->objectIdType()->toString();
        $this->objectType = $message->objectType()->toString();
        $this->eventId = $message->eventId()->toString();
        $this->dateTime = $message->dateTime->toString();
        $this->metaData = $this->serializer->serialize($message->metaData());
        $this->eventData = $this->serializer->serialize($message->eventData());
        $sequence = $message->sequence();
        assert(Test::isInt($sequence), 'Sequence must be an integer');
        $this->sequence = $sequence;
    }

    /**
     * Retrieves the object ID
     *
     * @return string
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Retrieves the object ID type
     *
     * @return string
     */
    public function getObjectIdType()
    {
        return $this->objectIdType;
    }

    /**
     * Retrieves the object type
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * Retrieves the event ID
     *
     * @return string
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Retrieves the date/time
     *
     * @return string
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Retrieves the serialized meta data
     *
     * @return string
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * Retrieves the serialized event data
     *
     * @return string
     */
    public function getEventData()
    {
        return $this->eventData;
    }

    /**
     * Retrieves the sequence number
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Creates an event message
     *
     * @return EventMessage
     */
    public function toEventMessage()
    {
        $idClass = Type::create($this->objectIdType)->toClassName();
        $objectId = $idClass::fromString($this->objectId);
        $objectType = Type::create($this->objectType);
        $eventId = EventId::fromString($this->eventId);
        $dateTime = DateTime::fromString($this->dateTime);
        $metaData = $this->serializer->deserialize($this->metaData);
        $eventData = $this->serializer->deserialize($this->eventData);
        $sequence = $this->sequence;

        return new EventMessage($eventId, $objectId, $objectType, $dateTime, $metaData, $eventData, $sequence);
    }
}
