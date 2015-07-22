<?php

namespace Novuso\Common\Domain\Model\Api;

use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\TypeException;

/**
 * Serializer is the interface for a parsable object serializer
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Serializer
{
    /**
     * Serializes an object
     *
     * @param Parsable $object The object
     *
     * @return string
     */
    public static function serialize(Parsable $object);

    /**
     * Deserializes an object
     *
     * @param string $state The serialized object
     *
     * @return Parsable
     *
     * @throws TypeException When state is not a string
     * @throws DomainException When the string is invalid
     */
    public static function deserialize($state);
}
