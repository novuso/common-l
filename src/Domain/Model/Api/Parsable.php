<?php

namespace Novuso\Common\Domain\Model\Api;

use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\TypeException;

/**
 * Parsable is the interface for a string represented object
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Parsable
{
    /**
     * Creates an instance from a string representation
     *
     * @param string $state The string representation
     *
     * @return Parsable
     *
     * @throws TypeException When state is not a string
     * @throws DomainException When the string is invalid
     */
    public static function fromString($state);

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString();
}
