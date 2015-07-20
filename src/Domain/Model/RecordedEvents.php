<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Event\Api\Event;
use Novuso\Common\Domain\Event\EventCollection;
use Novuso\System\Type\Contract;

/**
 * RecordedEvents provides methods for recording domain events
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
trait RecordedEvents
{
    /**
     * Event collection
     *
     * @var EventCollection|null
     */
    protected $eventCollection;

    /**
     * Records a domain event
     *
     * @param Event $eventData The event data
     * @param array $metaData  The meta data
     *
     * @return void
     */
    public function recordThat(Event $eventData, array $metaData = [])
    {
        $this->eventCollection()->add($eventData, $metaData);
    }

    /**
     * Retrieves recorded event messages
     *
     * @return EventStream
     */
    public function getRecordedEvents()
    {
        return $this->eventCollection()->eventStream();
    }

    /**
     * Checks if there are recorded event messages
     *
     * @return bool
     */
    public function hasRecordedEvents()
    {
        return !($this->eventCollection()->isEmpty());
    }

    /**
     * Clears recorded event messages
     *
     * @return void
     */
    public function commitEvents()
    {
        $this->eventCollection()->commitEvents();
    }

    /**
     * Retrieves the event collection
     *
     * @return EventCollection
     */
    protected function eventCollection()
    {
        if ($this->eventCollection === null) {
            $this->eventCollection = new EventCollection($this->id(), Contract::create($this));
        }

        return $this->eventCollection;
    }
}
