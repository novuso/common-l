<?php

namespace Novuso\Common\Domain\EventStore;

use Countable;
use Novuso\System\Utility\Test;

/**
 * StreamData represents a persisted event stream
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class StreamData implements Countable
{
    /**
     * Stream version
     *
     * @var int|null
     */
    protected $version;

    /**
     * Stored events
     *
     * @var StoredEvent[]
     */
    protected $events = [];

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return count($this->events);
    }

    /**
     * Sets the version
     *
     * @param int $version The version
     *
     * @return void
     */
    public function setVersion($version)
    {
        assert(Test::isInt($version), 'Version must be an integer');

        $this->version = $version;
    }

    /**
     * Retrieves the version
     *
     * @return int|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Adds events
     *
     * @param array $events The events
     *
     * @return void
     */
    public function addEvents(array $events)
    {
        foreach ($events as $event) {
            $this->addEvent($event);
        }
    }

    /**
     * Adds an event
     *
     * @param StoredEvent $event The event
     *
     * @return void
     */
    public function addEvent(StoredEvent $event)
    {
        $sequence = $event->getSequence();

        assert(
            !Test::keyIsset($this->events, $sequence),
            sprintf('An event with sequence %s is already committed', $sequence)
        );

        $this->events[$sequence] = $event;
    }

    /**
     * Retrieves events
     *
     * @return StoredEvent[]
     */
    public function getEvents()
    {
        return $this->events;
    }
}
