<?php

namespace Novuso\Common\Domain\EventStore;

use Novuso\System\Utility\Test;

/**
 * StreamData represents a persisted event stream
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class StreamData
{
    /**
     * Object ID
     *
     * @var string
     */
    protected $objectId;

    /**
     * Object type
     *
     * @var string
     */
    protected $objectType;

    /**
     * Stream version
     *
     * @var int
     */
    protected $version;

    /**
     * Stored events
     *
     * @var StoredEvent[]
     */
    protected $events = [];

    /**
     * Constructs StreamData
     *
     * @param string $objectId     The object ID
     * @param string $objectType   The object type
     */
    public function __construct($objectId, $objectType)
    {
        $this->objectId = $objectId;
        $this->objectType = $objectType;
    }

    /**
     * Retrieves the object ID
     *
     * @return string
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Retrieves the object type
     *
     * @return string
     */
    public function getObjectType()
    {
        return $this->objectType;
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

        if (!isset($this->events[$sequence])) {
            $this->events[$sequence] = $event;
        }
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
