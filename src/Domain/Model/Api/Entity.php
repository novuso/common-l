<?php

namespace Novuso\Common\Domain\Model\Api;

use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;

/**
 * Entity is the interface for a domain entity
 *
 * Implementations must adhere to entity characteristics:
 *
 * * It models a thing in the domain
 * * It is mutable but should not expose properties through setter methods
 * * It is not fundamentally defined by attributes, but by a thread of
 *   continuity and identity
 * * It can be compared with others using identity equality
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Entity extends Comparable, Equatable
{
    /**
     * Retrieves the identifier
     *
     * @return Identifier
     */
    public function id();

    /**
     * Compares to another object
     *
     * The passed object must be an instance of the same type.
     *
     * The method should return 0 for values considered equal, return -1 if
     * this instance is less than the passed value, and return 1 if this
     * instance is greater than the passed value.
     *
     * @param mixed $object The object
     *
     * @return int
     */
    public function compareTo($object);

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
