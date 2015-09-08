<?php

namespace Novuso\Common\Domain\Model\Api;

use JsonSerializable;
use Novuso\System\Type\Equatable;
use Serializable;

/**
 * Value is the interface for a domain value object
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
interface Value extends Equatable, JsonSerializable, Serializable
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

    /**
     * Checks if an object equals this instance
     *
     * The passed object must be an instance of the same type.
     *
     * The method should return false for invalid object types, rather than
     * throw an exception.
     *
     * @param mixed $object The object
     *
     * @return bool
     */
    public function equals($object);

    /**
     * Retrieves a string representation for hashing
     *
     * The returned value must behave in a way consistent with the same
     * object's equals() method.
     *
     * A given object must consistently report the same hash value (unless it
     * is changed so that the new version is no longer considered "equal" to
     * the old), and two objects which equals() says are equal must report the
     * same hash value.
     *
     * @return string
     */
    public function hashValue();
}
