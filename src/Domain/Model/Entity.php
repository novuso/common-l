<?php

namespace Novuso\Common\Domain\Model;

use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;

/**
 * Entity is the interface for a domain entity
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
interface Entity extends Comparable, Equatable
{
    /**
     * Retrieves the identifier
     *
     * @return Identifier
     */
    public function id();
}
