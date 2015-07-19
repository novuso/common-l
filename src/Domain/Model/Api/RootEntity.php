<?php

namespace Novuso\Common\Domain\Model\Api;

use Novuso\Common\Domain\Event\Api\EventStream;

/**
 * RootEntity is the interface for a root entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface RootEntity extends Entity, RecordsEvents
{
    /**
     * Retrieves the concurrency version
     *
     * @return int
     */
    public function concurrencyVersion();

    /**
     * Retrieves recorded event messages
     *
     * @return EventStream
     */
    public function getRecordedEvents();

    /**
     * Checks if there are recorded event messages
     *
     * @return bool
     */
    public function hasRecordedEvents();

    /**
     * Clears recorded event messages
     *
     * @return void
     */
    public function commitEvents();
}
