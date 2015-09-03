<?php

namespace Novuso\Common\Domain\Messaging\Command;

use JsonSerializable;
use Serializable;

/**
 * Command is the interface for a domain command
 *
 * Implementations must adhere to command characteristics:
 *
 * * It describes an imperative request to the domain
 * * It is maintained as immutable
 * * It may hold references to value objects, primitives, and identifiers
 * * It may contain metadata that is not part of the actual message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Command extends JsonSerializable, Serializable
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
