<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Messaging\Event\EventStream;

/**
 * RootEntity is the interface for an aggregate root entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
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
     * Records a domain event
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     */
    public function recordThat(DomainEvent $domainEvent);

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
