<?php

namespace Novuso\Common\Domain\Model;

use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;

/**
 * Entity is the interface for a domain entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
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
