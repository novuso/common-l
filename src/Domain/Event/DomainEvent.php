<?php

namespace Novuso\Common\Domain\Event;

use Novuso\System\Exception\DomainException;
use Novuso\System\Serialization\Serializable;

/**
 * DomainEvent is the interface for a domain event
 *
 * Implementations must adhere to event characteristics:
 *
 * * It describes something that has happened in the past
 * * It is maintained as immutable
 * * It may hold references to value objects, primitives, and identifiers
 * * It is encodable for communication with other systems
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface DomainEvent extends Serializable
{
    /**
     * Creates instance from serialized representation
     *
     * @param array $data The serialized representation
     *
     * @return DomainEvent
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
