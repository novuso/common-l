<?php

namespace Novuso\Common\Domain\Model;

use JsonSerializable;
use Novuso\System\Type\Equatable;
use Serializable;

/**
 * ValueObject is the interface for a domain value object
 *
 * Implementations must adhere to value characteristics:
 *
 * * It measures, quantifies, or describes a thing in the domain
 * * It is maintained as immutable
 * * It models a conceptual whole by composing related attributes as an
 *   integral unit
 * * It is completely replaceable when the measurement or description changes
 * * It can be compared with others using value equality
 * * It supplies its collaborators with side-effect-free behavior
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface ValueObject extends Equatable, JsonSerializable, Serializable
{
    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString();

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString();

    /**
     * Retrieves a value for JSON encoding
     *
     * @return mixed
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
