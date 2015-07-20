<?php

namespace Novuso\Common\Application\Command;

use Novuso\System\Serialization\Serializable;

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
interface Command extends Serializable
{
}
