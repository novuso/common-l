<?php

namespace Novuso\Common\Domain\Identifier;

/**
 * IdGenerator is the interface for an ID generator
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface IdGenerator
{
    /**
     * Generates a new identifier
     *
     * @return Identifier
     */
    public static function generate();
}
