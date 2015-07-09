<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Event;

/**
 * RecordedEvents provides methods for recording domain events
 *
 * Methods for: Novuso\Common\Domain\Model\AggregateRoot
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
trait RecordedEvents
{
    /**
     * Recorded events
     *
     * @var EventMessage[]
     */
    protected $recordedEvents = [];

    /**
     * Retrieves recorded event messages
     *
     * @return EventMessages
     */
    public function getRecordedEvents(): EventMessages
    {
        $eventMessages = new EventMessages($this->recordedEvents);
        $this->recordedEvents = [];

        return $eventMessages;
    }

    /**
     * Checks if there are recorded events
     *
     * @return bool
     */
    public function hasRecordedEvents(): bool
    {
        return !empty($this->recordedEvents);
    }

    /**
     * Records an event
     *
     * @param DomainEvent $event The domain event
     *
     * @return void
     */
    public function recordThat(DomainEvent $event)
    {
        $this->recordedEvents[] = EventMessageFactory::create($event);
    }
}
