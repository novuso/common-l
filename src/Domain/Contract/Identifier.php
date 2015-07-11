<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Contract;

use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;

/**
 * Identifier is the interface for a unique identifier
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Identifier extends Comparable, Value
{
    /**
     * Generates a new identifier
     *
     * @return Identifier
     */
    public static function generate(): Identifier;

    /**
     * Creates an instance from a string representation
     *
     * @param string $string The string representation
     *
     * @return Identifier
     *
     * @throws DomainException When the string is not valid
     */
    public static function fromString(string $string): Identifier;
}
