<?php

namespace Novuso\Common\Domain\Model\Api;

use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Type\Comparable;

/**
 * Identifier is the interface for a domain identifier
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Identifier extends Comparable, Value
{
    /**
     * Creates an instance from a string representation
     *
     * @param string $id The string representation
     *
     * @return Identifier
     *
     * @throws TypeException When id is not a string
     * @throws DomainException When id is not valid
     */
    public static function fromString($id);
}
