<?php

namespace Novuso\Common\Domain\Entity;

use Novuso\Common\Domain\Event\EventStream;

/**
 * RootEntity is the interface for a root entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface RootEntity extends Entity
{
    /**
     * Retrieves the concurrency version
     *
     * @return int
     */
    public function concurrencyVersion();

    /**
     * Removes and returns recorded event messages
     *
     * @return EventStream
     */
    public function extractRecordedEvents();

    /**
     * Checks if there are recorded event messages
     *
     * @return bool
     */
    public function hasRecordedEvents();
}
