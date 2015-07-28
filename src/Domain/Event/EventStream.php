<?php

namespace Novuso\Common\Domain\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Novuso\Common\Domain\Identifier\Identifier;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Serialization\Serializable;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;
use Traversable;

/**
 * EventStream is a stream of event messages
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EventStream implements Countable, IteratorAggregate, Serializable
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
     * Event Messages
     *
     * @var EventMessage[]
     */
    protected $messages;

    /**
     * Committed version
     *
     * @var int|null
     */
    protected $committed;

    /**
     * Version number
     *
     * @var int|null
     */
    protected $version;

    /**
     * Constructs EventStream
     *
     * @param Identifier     $objectId   The object ID
     * @param Type           $objectType The object type
     * @param int|null       $committed  The committed version
     * @param int|null       $version    The version number
     * @param EventMessage[] $messages   A list of event messages
     */
    public function __construct(Identifier $objectId, Type $objectType, $committed, $version, array $messages)
    {
        assert(
            Test::isListOf($messages, EventMessage::class),
            sprintf('Invalid event messages: %s', VarPrinter::toString($messages))
        );

        $this->objectId = $objectId;
        $this->objectIdType = Type::create($objectId);
        $this->objectType = $objectType;
        $this->committed = $committed;
        $this->version = $version;
        $this->messages = [];

        foreach ($messages as $message) {
            $this->addEventMessage($message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $objectIdClass = Type::create($data['object_id']['type'])->toClassName();
        $objectId = $objectIdClass::fromString($data['object_id']['identifier']);
        $objectType = Type::create($data['object_type']);
        $committed = $data['committed'];
        $version = $data['version'];

        $messages = [];

        foreach ($data['messages'] as $mData) {
            $eventClass = Type::create($mData['event_data']['type'])->toClassName();
            $messages[] = new EventMessage(
                EventId::fromString($mData['event_id']),
                $objectId,
                $objectType,
                DateTime::fromString($mData['date_time']),
                MetaData::deserialize($mData['meta_data']),
                $eventClass::deserialize($mData['event_data']['data']),
                $mData['sequence']
            );
        }

        return new self($objectId, $objectType, $committed, $version, $messages);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $messages = [];

        foreach ($this->messages as $message) {
            $messages[] = [
                'event_id'   => $message->eventId()->toString(),
                'date_time'  => $message->dateTime()->toString(),
                'meta_data'  => $message->metaData()->serialize(),
                'event_data' => [
                    'type' => $message->eventType()->toString(),
                    'data' => $message->eventData()->serialize()
                ],
                'sequence'   => $message->sequence()
            ];
        }

        return [
            'object_id'   => [
                'type'       => $this->objectIdType->toString(),
                'identifier' => $this->objectId->toString()
            ],
            'object_type' => $this->objectType->toString(),
            'committed'   => $this->committed,
            'version'     => $this->version,
            'messages'    => $messages
        ];
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->messages);
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return count($this->messages);
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
     * Retrieves an array copy of messages
     *
     * @return EventMessage[]
     */
    public function messages()
    {
        $array = $this->messages;

        return $array;
    }

    /**
     * Retrieves the committed version
     *
     * @return int|null
     */
    public function committed()
    {
        return $this->committed;
    }

    /**
     * Retrieves the version number
     *
     * @return int|null
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->messages);
    }

    /**
     * Adds an event message
     *
     * @param EventMessage $message The event message
     *
     * @return void
     */
    private function addEventMessage(EventMessage $message)
    {
        $this->messages[] = $message;
    }
}
