<?php

namespace Novuso\Common\Domain\Model\Api;

use Novuso\Common\Domain\Messaging\Event\EventStream;

/**
 * RootEntity is the interface for an aggregate root entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface RootEntity extends Entity
{
    /**
     * Retrieves the committed version
     *
     * @return int
     */
    public function committedVersion();

    /**
     * Retrieves and clears recorded event messages
     *
     * @return EventStream
     */
    public function extractRecordedEvents();

    /**
     * Retrieves recorded event messages
     *
     * @return EventStream
     */
    public function getRecordedEvents();

    /**
     * Checks if there are recorded events
     *
     * @return bool
     */
    public function hasRecordedEvents();

    /**
     * Clears events and updates version
     *
     * @return void
     */
    public function clearRecordedEvents();
}
