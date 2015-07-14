<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Event;

use JsonSerializable;
use Novuso\Common\Domain\Contract\Identifier;
use Serializable;

/**
 * DomainEvent is the base class for domain events
 *
 * Implementations must adhere to event characteristics:
 *
 * * It describes something that has happened in the past
 * * It is maintained as immutable
 * * It may hold references to value objects, primitives, and identifiers
 * * It is encodable for communication with other systems
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class DomainEvent implements JsonSerializable, Serializable
{
    /**
     * Retrieves the event version
     *
     * @return int
     */
    abstract public function version(): int;

    /**
     * Retrieves the ID of the aggregate root
     *
     * @return Identifier
     */
    abstract public function aggregateId(): Identifier;

    /**
     * Retrieves an array representation
     *
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
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
}
