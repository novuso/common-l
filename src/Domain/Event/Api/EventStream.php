<?php

namespace Novuso\Common\Domain\Event\Api;

use Countable;
use IteratorAggregate;
use Traversable;

/**
 * EventStream is the interface for an event message stream
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface EventStream extends Countable, IteratorAggregate
{
    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count();

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator();
}
