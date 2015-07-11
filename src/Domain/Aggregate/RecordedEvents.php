<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Aggregate;

use Novuso\Common\Domain\Event\{
    DomainEvent,
    EventMessage,
    EventMessages,
    EventMessageFactory
};

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
    public function extractRecordedEvents(): EventMessages
    {
        $eventMessages = new EventMessages($this->recordedEvents);
        $this->recordedEvents = [];

        return $eventMessages;
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
