<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Event;

use JsonSerializable;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\{Comparable, Equatable};
use Novuso\System\Utility\Test;
use Serializable;

/**
 * EventHeaders contains the metadata for an event message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EventHeaders implements Comparable, Equatable, JsonSerializable, Serializable
{
    /**
     * Event ID
     *
     * @var EventId
     */
    protected $id;

    /**
     * Timestamp
     *
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Event type
     *
     * @var string
     */
    protected $type;

    /**
     * Constructs EventHeaders
     *
     * @param EventId  $id       The event id
     * @param DateTime $dateTime The timestamp
     * @param string   $type     The event type
     */
    public function __construct(EventId $id, DateTime $dateTime, string $type)
    {
        $this->id = $id;
        $this->dateTime = $dateTime;
        $this->type = $type;
    }

    /**
     * Retrieves the event identifier
     *
     * @return EventId
     */
    public function id(): EventId
    {
        return $this->id;
    }

    /**
     * Retrieves the event date\time
     *
     * @return DateTime
     */
    public function dateTime(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * Retrieves the event type
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Retrieves an array representation
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'ID'       => $this->id->toString(),
            'DateTime' => $this->dateTime->toString(),
            'Type'     => $this->type
        ];
    }

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString(): string
    {
        $output = [];
        foreach ($this->toArray() as $name => $value) {
            $output[] = sprintf('%s: %s', $name, $value);
        }

        return trim(implode("\r\n", $output));
    }

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Retrieves a JSON representation
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->jsonSerialize());
    }

    /**
     * Retrieves a value for JSON encoding
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Retrieves a serialized representation
     *
     * @return string
     */
    public function serialize(): string
    {
        return serialize(get_object_vars($this));
    }

    /**
     * Handles construction from a serialized representation
     *
     * @param string $str The serialized representation
     *
     * @return void
     */
    public function unserialize($str)
    {
        $properties = unserialize($str);
        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object): int
    {
        if ($this === $object) {
            return 0;
        }

        assert(Test::sameType($this, $object), sprintf('Comparison requires instance of %s', static::class));

        $comp = $this->dateTime->compareTo($object->dateTime());

        if ($comp > 0) {
            return 1;
        }
        if ($comp < 0) {
            return -1;
        }

        return $this->id->compareTo($object->id());
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object): bool
    {
        if ($this === $object) {
            return true;
        }

        if (!Test::sameType($this, $object)) {
            return false;
        }

        return $this->id->equals($object->id());
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return $this->id->hashValue();
    }
}
