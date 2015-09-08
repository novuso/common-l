<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Model\Api\Entity;
use Novuso\System\Utility\Test;

/**
 * DomainEntity is the base class for a domain entity
 *
 * Implementations must adhere to entity characteristics:
 *
 * * It models a thing in the domain
 * * It is mutable but should not expose properties through setter methods
 * * It is not fundamentally defined by attributes, but by a thread of
 *   continuity and identity
 * * It can be compared with others using identity equality
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class DomainEntity implements Entity
{
    use Identity;
}
