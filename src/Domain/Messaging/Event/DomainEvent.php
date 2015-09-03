<?php

namespace Novuso\Common\Domain\Messaging\Event;

use JsonSerializable;
use Serializable;

/**
 * DomainEvent is the interface for a domain event
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
 */
interface DomainEvent extends JsonSerializable, Serializable
{
    /**
     * Retrieves a value for JSON encoding
     *
     * @return array
     */
    public function jsonSerialize();

    /**
     * Retrieves a serialized representation
     *
     * @return string
     */
    public function serialize();

    /**
     * Handles construction from a serialized representation
     *
     * @param string $serialized The serialized representation
     *
     * @return void
     */
    public function unserialize($serialized);
}
