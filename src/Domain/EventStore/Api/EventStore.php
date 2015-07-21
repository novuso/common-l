<?php

namespace Novuso\Common\Domain\EventStore\Api;

use Novuso\Common\Domain\Event\EventStream;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\System\Type\Type;

/**
 * EventStore is the interface for an event store
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface EventStore
{
    /**
     * Loads event stream by ID and type
     *
     * @param Identifier $id   The stream ID
     * @param Type       $type The stream type
     *
     * @return EventStream
     */
    public function load(Identifier $id, Type $type);

    /**
     * Appends events in a stream to the store
     *
     * @param EventStream $events The event stream
     *
     * @return void
     */
    public function append(EventStream $events);
}
