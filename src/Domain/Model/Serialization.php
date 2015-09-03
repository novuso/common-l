<?php

namespace Novuso\Common\Domain\Model;

/**
 * Serialization provides a serializable implementation
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
trait Serialization
{
    /**
     * Retrieves a serialized representation
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(get_object_vars($this));
    }

    /**
     * Handles construction from a serialized representation
     *
     * @param string $serialized The serialized representation
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        $properties = unserialize($serialized);
        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }
    }
}
