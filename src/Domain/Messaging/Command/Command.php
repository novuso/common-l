<?php

namespace Novuso\Common\Domain\Messaging\Command;

use Novuso\System\Exception\DomainException;
use Novuso\System\Serialization\Serializable;

/**
 * Command is the interface for a domain command
 *
 * Implementations must adhere to command characteristics:
 *
 * * It describes an imperative request to the domain
 * * It is maintained as immutable
 * * It may hold references to value objects, primitives, and identifiers
 * * It may contain metadata that is not part of the actual message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Command extends Serializable
{
    /**
     * Creates instance from serialized representation
     *
     * @param array $data The serialized representation
     *
     * @return Command
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
