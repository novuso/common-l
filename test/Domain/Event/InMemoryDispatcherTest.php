<?php

namespace Novuso\Test\Common\Domain\Event;

use Novuso\Common\Domain\Event\EventId;
use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\InMemoryDispatcher;
use Novuso\Common\Domain\Event\MetaData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\EventLogSubscriber;
use Novuso\Test\Common\Doubles\User;
use Novuso\Test\Common\Doubles\UserId;
use Novuso\Test\Common\Doubles\UserRegisteredEvent;
use Novuso\Test\Common\Doubles\TestSubscriber;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Event\InMemoryDispatcher
 */
class InMemoryDispatcherTest extends PHPUnit_Framework_TestCase
{
    protected $dispatcher;

    public function setUp()
    {
        $this->dispatcher = new InMemoryDispatcher();
    }

    public function test_that_event_is_dispatched_to_handler()
    {
        $subscriber = new TestSubscriber();
        $this->dispatcher->attach($subscriber);
        $eventId = EventId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $userId = UserId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $userType = Type::create(User::class);
        $dateTime = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $eventData = new UserRegisteredEvent('Leeroy Jenkins', 'ljenkins');
        $sequence = 0;
        $eventMessage = new EventMessage(
            $eventId,
            $userId,
            $userType,
            $dateTime,
            $metaData,
            $eventData,
            $sequence
        );
        $this->dispatcher->dispatch($eventMessage);
        $this->assertTrue($subscriber->welcomeSent());
    }

    public function test_that_detached_handler_is_not_called()
    {
        $subscriber = new TestSubscriber();
        $this->dispatcher->attach($subscriber);
        $eventId = EventId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $userId = UserId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $userType = Type::create(User::class);
        $dateTime = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $eventData = new UserRegisteredEvent('Leeroy Jenkins', 'ljenkins');
        $sequence = 0;
        $eventMessage = new EventMessage(
            $eventId,
            $userId,
            $userType,
            $dateTime,
            $metaData,
            $eventData,
            $sequence
        );
        $this->dispatcher->detach($subscriber);
        $this->dispatcher->dispatch($eventMessage);
        $this->assertFalse($subscriber->welcomeSent());
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers()
    {
        $subscriber = new TestSubscriber();
        $this->dispatcher->attach($subscriber);
        $this->assertTrue($this->dispatcher->hasHandlers());
    }

    public function test_that_remove_handler_does_not_error_when_handler_not_registered()
    {
        $this->dispatcher->removeHandler('foo', function () {});
        $this->assertFalse($this->dispatcher->hasHandlers());
    }

    public function test_that_all_events_key_subscribes_to_any_event()
    {
        $subscriber = new EventLogSubscriber();
        $this->dispatcher->attach($subscriber);
        $eventId = EventId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $userId = UserId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $userType = Type::create(User::class);
        $dateTime = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $eventData = new UserRegisteredEvent('Leeroy Jenkins', 'ljenkins');
        $sequence = 0;
        $eventMessage = new EventMessage(
            $eventId,
            $userId,
            $userType,
            $dateTime,
            $metaData,
            $eventData,
            $sequence
        );
        $this->dispatcher->dispatch($eventMessage);
        $logs = $subscriber->getLogs();
        $expected = <<<EOT
{
    "object_id": {
        "type": "Novuso.Test.Common.Doubles.UserId",
        "identifier": "014ec11d-2f21-4d33-a624-5df1196a4f85"
    },
    "object_type": "Novuso.Test.Common.Doubles.User",
    "event_id": "014ec11e-4343-49cd-9b7a-cdd4ced5cedc",
    "date_time": "2015-01-01T13:12:31.045234[America/Chicago]",
    "meta_data": {
        "ip_address": "127.0.0.1"
    },
    "event_data": {
        "type": "Novuso.Test.Common.Doubles.UserRegisteredEvent",
        "data": {
            "full_name": "Leeroy Jenkins",
            "username": "ljenkins"
        }
    },
    "sequence": 0
}
EOT;
        $this->assertSame($expected, $logs[0]);
    }
}
