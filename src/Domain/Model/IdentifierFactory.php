<?php

namespace Novuso\Common\Domain\Model;

/**
 * IdentifierFactory is the interface for an identifier factory
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
interface IdentifierFactory
{
    /**
     * Generates a unique identifier
     *
     * @return Identifier
     */
    public static function generate();
}
