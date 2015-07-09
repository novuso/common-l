<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Event;

use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Utility\ClassName;

/**
 * EventMessageFactory is a factory for event messages
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EventMessageFactory
{
    /**
     * Creates an event message from a domain event
     *
     * @param DomainEvent $event The domain event
     *
     * @return EventMessage
     */
    public static function create(DomainEvent $event): EventMessage
    {
        $dateTime = DateTime::now();
        $eventId = EventId::generate();
        $type = ClassName::underscore($event);
        $headers = new EventHeaders($eventId, $dateTime, $type);

        return new EventMessage($headers, $event, $type);
    }
}
