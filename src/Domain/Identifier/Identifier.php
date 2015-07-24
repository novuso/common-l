<?php

namespace Novuso\Common\Domain\Identifier;

use Novuso\Common\Domain\Value\Value;
use Novuso\System\Type\Comparable;

/**
 * Identifier is the interface for a domain identifier
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Identifier extends Comparable, Value
{
}
