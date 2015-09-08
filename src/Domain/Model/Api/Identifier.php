<?php

namespace Novuso\Common\Domain\Model\Api;

use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;

/**
 * Identifier is the interface for a domain identifier
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Identifier extends Comparable, Value
{
    /**
     * Creates instance from a string representation
     *
     * @param string $id The ID string
     *
     * @return Identifier
     *
     * @throws DomainException When the string is invalid
     */
    public static function fromString($id);

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
}
