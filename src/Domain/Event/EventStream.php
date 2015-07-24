<?php

namespace Novuso\Common\Domain\Event;

use Countable;
use IteratorAggregate;
use Novuso\Common\Domain\Identifier\Identifier;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\Common\Domain\Value\ValueSerializer;
use Novuso\System\Collection\SortedSet;
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
     * Associated ID
     *
     * @var Identifier
     */
    protected $id;

    /**
     * Associated Type
     *
     * @var Type
     */
    protected $type;

    /**
     * Event Messages
     *
     * @var SortedSet
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
     * @param Identifier     $id        The associated ID
     * @param Type           $type      The associated type
     * @param int|null       $committed The committed version
     * @param int|null       $version   The version number
     * @param EventMessage[] $messages  A list of event messages
     */
    public function __construct(Identifier $id, Type $type, $committed, $version, array $messages)
    {
        assert(
            Test::isListOf($messages, EventMessage::class),
            sprintf('Invalid event messages: %s', VarPrinter::toString($messages))
        );

        $this->id = $id;
        $this->type = $type;
        $this->committed = $committed;
        $this->version = $version;
        $this->messages = SortedSet::comparable(EventMessage::class);

        foreach ($messages as $message) {
            $this->messages->add($message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $id = ValueSerializer::deserialize($data['id']);
        $type = Type::create($data['type']);
        $committed = $data['committed'];
        $version = $data['version'];

        $messages = [];

        foreach ($data['messages'] as $mData) {
            $eventClass = Type::create($mData['eventType'])->toClassName();
            $messages[] = new EventMessage(
                EventId::fromString($mData['eventId']),
                $id,
                $type,
                DateTime::fromString($mData['dateTime']),
                MetaData::deserialize($mData['metaData']),
                $eventClass::deserialize($mData['eventData']),
                $mData['sequence']
            );
        }

        return new self($id, $type, $committed, $version, $messages);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $messages = [];

        foreach ($this->messages as $message) {
            $messages[] = [
                'sequence'  => $message->sequence(),
                'eventId'   => $message->eventId()->toString(),
                'eventType' => $message->eventType()->toString(),
                'dateTime'  => $message->dateTime()->toString(),
                'metaData'  => $message->metaData()->serialize(),
                'eventData' => $message->eventData()->serialize()
            ];
        }

        return [
            'id'        => ValueSerializer::serialize($this->id),
            'type'      => $this->type->toString(),
            'committed' => $this->committed,
            'version'   => $this->version,
            'messages'  => $messages
        ];
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->messages->isEmpty();
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return $this->messages->count();
    }

    /**
     * Retrieves the associated ID
     *
     * @return Identifier
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Retrieves the associated type
     *
     * @return Type
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Retrieves an array copy of messages
     *
     * @return EventMessage[]
     */
    public function messages()
    {
        $array = [];

        foreach ($this->messages as $message) {
            $array[] = $message;
        }

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
        return $this->messages->getIterator();
    }
}
