<?php

namespace Novuso\Common\Domain\Model;

use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;

/**
 * Identifier is the interface for a domain identifier
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Identifier extends Comparable, ValueObject
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
}
