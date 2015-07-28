<?php

namespace Novuso\Test\Common\Domain\EventStore;

use Novuso\Common\Domain\Event\EventId;
use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\MetaData;
use Novuso\Common\Domain\EventStore\StoredEvent;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\User;
use Novuso\Test\Common\Doubles\UserId;
use Novuso\Test\Common\Doubles\UserRegisteredEvent;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventStore\StoredEvent
 */
class StoredEventTest extends PHPUnit_Framework_TestCase
{
    protected $eventMessage;
    protected $storedEvent;

    public function setUp()
    {
        $eventId = EventId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $userId = UserId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $userType = Type::create(User::class);
        $dateTime = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $eventData = new UserRegisteredEvent('Leeroy Jenkins', 'ljenkins');
        $sequence = 0;
        $this->eventMessage = new EventMessage(
            $eventId,
            $userId,
            $userType,
            $dateTime,
            $metaData,
            $eventData,
            $sequence
        );
        $this->storedEvent = new StoredEvent($this->eventMessage, new JsonSerializer());
    }

    public function test_that_get_object_id_returns_expected_value()
    {
        $expected = '014ec11d-2f21-4d33-a624-5df1196a4f85';
        $this->assertSame($expected, $this->storedEvent->getObjectId());
    }

    public function test_that_get_object_id_type_returns_expected_value()
    {
        $expected = 'Novuso.Test.Common.Doubles.UserId';
        $this->assertSame($expected, $this->storedEvent->getObjectIdType());
    }

    public function test_that_get_object_type_returns_expected_value()
    {
        $expected = 'Novuso.Test.Common.Doubles.User';
        $this->assertSame($expected, $this->storedEvent->getObjectType());
    }

    public function test_that_get_event_id_returns_expected_value()
    {
        $expected = '014ec11e-4343-49cd-9b7a-cdd4ced5cedc';
        $this->assertSame($expected, $this->storedEvent->getEventId());
    }

    public function test_that_get_date_time_returns_expected_value()
    {
        $expected = '2015-01-01T13:12:31.045234[America/Chicago]';
        $this->assertSame($expected, $this->storedEvent->getDateTime());
    }

    public function test_that_get_meta_data_returns_expected_value()
    {
        $expected = '{"type":"Novuso.Common.Domain.Event.MetaData","data":{"ip_address":"127.0.0.1"}}';
        $this->assertSame($expected, $this->storedEvent->getMetaData());
    }

    public function test_that_get_event_data_returns_expected_value()
    {
        $expected = '{"type":"Novuso.Test.Common.Doubles.UserRegisteredEvent",'
            .'"data":{"full_name":"Leeroy Jenkins","username":"ljenkins"}}';
        $this->assertSame($expected, $this->storedEvent->getEventData());
    }

    public function test_that_get_sequence_returns_expected_value()
    {
        $this->assertSame(0, $this->storedEvent->getSequence());
    }

    public function test_that_to_event_message_returns_expected_instance()
    {
        $this->assertTrue($this->eventMessage->equals($this->storedEvent->toEventMessage()));
    }
}
