<?php

namespace Novuso\Common\Domain\EventStore;

use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\EventStream;
use Novuso\Common\Domain\EventStore\Exception\EventStoreException;
use Novuso\Common\Domain\EventStore\Exception\StreamNotFoundException;
use Novuso\Common\Domain\Identifier\Identifier;
use Novuso\System\Type\Type;

/**
 * EventStore is the interface for a domain event store
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface EventStore
{
    /**
     * Appends an event message
     *
     * @param EventMessage $eventMessage The event message
     *
     * @return void
     *
     * @throws EventStoreException When an error occurs during processing
     */
    public function appendEvent(EventMessage $eventMessage);

    /**
     * Appends events from a given event stream
     *
     * @param EventStream $eventStream The event stream
     *
     * @return void
     *
     * @throws EventStoreException When an error occurs during processing
     */
    public function appendStream(EventStream $eventStream);

    /**
     * Loads an event stream
     *
     * @param Identifier $objectId   The object ID
     * @param Type       $objectType The object type
     *
     * @return EventStream
     *
     * @throws StreamNotFoundException When the event stream is not found
     */
    public function loadStream(Identifier $objectId, Type $objectType);

    /**
     * Checks if an event stream exists
     *
     * @param Identifier $objectId   The object ID
     * @param Type       $objectType The object type
     *
     * @return bool
     */
    public function hasStream(Identifier $objectId, Type $objectType);
}
