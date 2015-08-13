<?php

namespace Novuso\Test\Common\Doubles\Application\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\Subscriber;

class EventLogSubscriber implements Subscriber
{
    protected $logs = [];

    public static function eventRegistration()
    {
        return [Subscriber::ALL_EVENTS => 'onAllEvents'];
    }

    public function onAllEvents(EventMessage $message)
    {
        $this->logs[] = $message->toString();
    }

    public function getLogs()
    {
        return $this->logs;
    }
}
