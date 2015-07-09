<?php declare(strict_types=1);

namespace Novuso\Common\Application\Command;

/**
 * Command is the interface for an application command
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
interface Command
{
    /**
     * Retrieves an array representation
     *
     * @return array
     */
    public function toArray(): array;
}
