<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Messaging\Event\EventStream;
use Novuso\System\Exception\OperationException;
use Novuso\System\Type\Type;

/**
 * EventRecords provides methods for recording aggregate events
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
trait EventRecords
{
    /**
     * Event collection
     *
     * @var EventCollection|null
     */
    private $eventCollection;

    /**
     * Committed version
     *
     * @var int|null
     */
    private $committedVersion;

    /**
     * Retrieves the identifier
     *
     * @return Identifier
     */
    abstract public function id();

    /**
     * Retrieves the committed version
     *
     * @return int
     */
    public function committedVersion()
    {
        if ($this->committedVersion === null) {
            $this->committedVersion = $this->eventCollection()->committedSequence();
        }

        return $this->committedVersion;
    }

    /**
     * Retrieves recorded event messages
     *
     * @return EventStream
     */
    public function getRecordedEvents()
    {
        return $this->eventCollection()->stream();
    }

    /**
     * Checks if there are recorded events
     *
     * @return bool
     */
    public function hasRecordedEvents()
    {
        return !($this->eventCollection()->isEmpty());
    }

    /**
     * Clears events and updates version
     *
     * @return void
     */
    public function clearRecordedEvents()
    {
        $eventCollection = $this->eventCollection();
        $eventCollection->commit();
        $this->committedVersion = $eventCollection->committedSequence();
    }

    /**
     * Records a domain event
     *
     * @param DomainEvent $domainEvent The domain event
     *
     * @return void
     */
    private function recordThat(DomainEvent $domainEvent)
    {
        $this->eventCollection()->record($domainEvent);
    }

    /**
     * Retrieves the event collection
     *
     * @return EventCollection
     */
    private function eventCollection()
    {
        if ($this->eventCollection === null) {
            $this->eventCollection = new EventCollection($this->id(), Type::create($this));
        }

        return $this->eventCollection;
    }

    /**
     * Initializes the committed version
     *
     * @param int $committedVersion The initial version
     *
     * @return void
     *
     * @throws OperationException When called with recorded events
     */
    private function initializeCommittedVersion($committedVersion)
    {
        if (!$this->eventCollection()->isEmpty()) {
            $message = 'Cannot initialize version after recording events';
            throw OperationException::create($message);
        }

        $this->eventCollection()->initializeSequence($committedVersion);
    }
}
