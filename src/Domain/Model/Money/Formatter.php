<?php

namespace Novuso\Common\Domain\Model\Money;

/**
 * Formatter is the interface for a money formatter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Formatter
{
    /**
     * Retrieves a formatted string for a monetary value
     *
     * @param Money $money The monetary value
     *
     * @return string
     */
    public function format(Money $money);
}
