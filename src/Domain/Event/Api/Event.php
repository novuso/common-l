<?php

namespace Novuso\Common\Domain\Event\Api;

use Novuso\System\Serialization\Serializable;

/**
 * Event is the interface for a domain event
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
interface Event extends Serializable
{
    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString();

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString();
}
