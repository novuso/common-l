<?php

namespace Novuso\Common\Domain\Messaging\Query;

use Novuso\System\Exception\DomainException;
use Novuso\System\Serialization\Serializable;

/**
 * Query is the interface for a domain query
 *
 * Implementations must adhere to query characteristics:
 *
 * * It describes an interrogatory request to the domain
 * * It is maintained as immutable
 * * It may hold references to value objects, primitives, and identifiers
 * * It may contain metadata that is not part of the actual message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Query extends Serializable
{
    /**
     * Creates instance from serialized representation
     *
     * @param array $data The serialized representation
     *
     * @return Query
     *
     * @throws DomainException When the data is invalid
     */
    public static function deserialize(array $data);

    /**
     * Retrieves serialized representation
     *
     * @return array
     */
    public function serialize();
}
