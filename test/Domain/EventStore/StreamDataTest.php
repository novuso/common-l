<?php

namespace Novuso\Test\Common\Domain\EventStore;

use Novuso\Common\Domain\Event\EventId;
use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\MetaData;
use Novuso\Common\Domain\EventStore\StoredEvent;
use Novuso\Common\Domain\EventStore\StreamData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\User;
use Novuso\Test\Common\Doubles\UserId;
use Novuso\Test\Common\Doubles\UserRegisteredEvent;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventStore\StreamData
 */
class StreamDataTest extends PHPUnit_Framework_TestCase
{
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
        $eventMessage = new EventMessage(
            $eventId,
            $userId,
            $userType,
            $dateTime,
            $metaData,
            $eventData,
            $sequence
        );
        $this->storedEvent = new StoredEvent($eventMessage, new JsonSerializer());
    }

    public function test_that_get_object_id_returns_expected_value()
    {
        $objectId = '014ec11d-2f21-4d33-a624-5df1196a4f85';
        $objectType = 'Novuso.Test.Common.Doubles.User';
        $data = new StreamData($objectId, $objectType);
        $this->assertSame($objectId, $data->getObjectId());
    }

    public function test_that_get_object_type_returns_expected_value()
    {
        $objectId = '014ec11d-2f21-4d33-a624-5df1196a4f85';
        $objectType = 'Novuso.Test.Common.Doubles.User';
        $data = new StreamData($objectId, $objectType);
        $this->assertSame($objectType, $data->getObjectType());
    }

    public function test_that_version_can_be_modified_from_outside()
    {
        $objectId = '014ec11d-2f21-4d33-a624-5df1196a4f85';
        $objectType = 'Novuso.Test.Common.Doubles.User';
        $data = new StreamData($objectId, $objectType);
        $data->setVersion(12);
        $this->assertSame(12, $data->getVersion());
    }

    public function test_that_events_are_stored_with_sequence_keys()
    {
        $objectId = '014ec11d-2f21-4d33-a624-5df1196a4f85';
        $objectType = 'Novuso.Test.Common.Doubles.User';
        $data = new StreamData($objectId, $objectType);
        $data->setVersion(0);
        $data->addEvents([$this->storedEvent]);
        $events = $data->getEvents();
        $this->assertSame($this->storedEvent, $events[0]);
    }
}
