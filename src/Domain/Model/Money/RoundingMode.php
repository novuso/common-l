<?php

namespace Novuso\Common\Domain\Model\Money;

use Novuso\System\Type\Enum;

/**
 * RoundingMode represents a numeric rounding mode
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
final class RoundingMode extends Enum
{
    /**
     * Round up when half way
     *
     * @var int
     */
    const HALF_UP = PHP_ROUND_HALF_UP;

    /**
     * Round down when half way
     *
     * @var int
     */
    const HALF_DOWN = PHP_ROUND_HALF_DOWN;

    /**
     * Round towards the next even value
     *
     * @var int
     */
    const HALF_EVEN = PHP_ROUND_HALF_EVEN;

    /**
     * Round towards the next odd value
     *
     * @var int
     */
    const HALF_ODD = PHP_ROUND_HALF_ODD;
}
