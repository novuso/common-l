<?php

namespace Novuso\Common\Domain\Model;

/**
 * StringCast handles string casting for string values
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
trait StringCast
{
    /**
     * Retrieves a string representation
     *
     * @return string
     */
    abstract public function toString();

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
