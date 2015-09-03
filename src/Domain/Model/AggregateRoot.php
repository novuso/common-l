<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Messaging\Event\EventStream;

/**
 * AggregateRoot is the interface for an aggregate root entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface AggregateRoot extends Entity
{
    /**
     * Retrieves the committed version
     *
     * @return int
     */
    public function committedVersion();

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
