<?php

namespace Novuso\Common\Domain\Messaging\Query;

use JsonSerializable;
use Serializable;

/**
 * Query is the interface for a domain query
 *
 * Implementations must adhere to query characteristics:
 *
 * * It describes an interrogatory request to the domain
 * * It is maintained as immutable
 * * It may hold references to value objects, primitives, and identifiers
 * * It may contain metadata that is not part of the actual message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Query extends JsonSerializable, Serializable
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
