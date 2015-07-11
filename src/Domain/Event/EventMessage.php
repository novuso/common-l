<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Event;

use JsonSerializable;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\{Comparable, Equatable};
use Novuso\System\Utility\Test;
use Serializable;

/**
 * EventMessage is a message wrapper for a domain event
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EventMessage implements Comparable, Equatable, JsonSerializable, Serializable
{
    /**
     * Event headers
     *
     * @var EventHeaders
     */
    protected $headers;

    /**
     * Domain event
     *
     * @var DomainEvent
     */
    protected $event;

    /**
     * Event type
     *
     * @var string
     */
    protected $type;

    /**
     * Constructs EventMessage
     *
     * @param EventHeaders $headers The event headers
     * @param DomainEvent  $event   The domain event
     * @param string       $type    The event type
     */
    public function __construct(EventHeaders $headers, DomainEvent $event, string $type)
    {
        $this->headers = $headers;
        $this->event = $event;
        $this->type = $type;
    }

    /**
     * Retrieves the event headers
     *
     * @return EventHeaders
     */
    public function headers(): EventHeaders
    {
        return $this->headers;
    }

    /**
     * Retrieves the domain event data
     *
     * @return DomainEvent
     */
    public function event(): DomainEvent
    {
        return $this->event;
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
            'headers' => $this->headers->toArray(),
            'data'    => $this->event->toArray()
        ];
    }

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->headers->toString()."\r\n".$this->event->toString();
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

        return $this->headers->compareTo($object->headers());
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

        return $this->headers->equals($object->headers());
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return $this->headers->hashValue();
    }
}
