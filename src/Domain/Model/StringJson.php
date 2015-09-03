<?php

namespace Novuso\Common\Domain\Model;

/**
 * StringJson handles JSON encoding for string values
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
trait StringJson
{
    /**
     * Retrieves a string representation
     *
     * @return string
     */
    abstract public function toString();

    /**
     * Retrieves a value for JSON encoding
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->toString();
    }
}
