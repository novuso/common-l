<?php

namespace Novuso\Test\Common\Domain\Event;

use Novuso\Common\Domain\Event\EventId;
use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\EventStream;
use Novuso\Common\Domain\Event\MetaData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\IpAddress;
use Novuso\Test\Common\Doubles\User;
use Novuso\Test\Common\Doubles\UserId;
use Novuso\Test\Common\Doubles\UserRegisteredEvent;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Event\EventStream
 */
class EventStreamTest extends PHPUnit_Framework_TestCase
{
    protected $userId;
    protected $userType;
    protected $metaData;
    protected $committed;
    protected $version;

    public function setUp()
    {
        $this->userId = UserId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $this->userType = Type::create(User::class);
        $this->metaData = new MetaData(['ipAddress' => '127.0.0.1']);
        $this->committed = 3;
        $this->version = 6;
    }

    public function test_that_it_is_serializable()
    {
        $serializer = new JsonSerializer();
        $eventMessages = $this->getEventMessages();
        $eventStream = new EventStream(
            $this->userId,
            $this->userType,
            $this->committed,
            $this->version,
            $eventMessages
        );
        $string = $serializer->serialize($eventStream);
        $readStream = $serializer->deserialize($string);
        $valid = true;
        $i = 0;
        foreach ($readStream as $eventMessage) {
            if (!$eventMessage->equals($eventMessages[$i])) {
                $valid = false;
            }
            $i++;
        }
        $this->assertTrue($valid);
    }

    public function test_that_is_empty_returns_true_when_empty()
    {
        $eventStream = new EventStream(
            $this->userId,
            $this->userType,
            $this->committed,
            $this->committed,
            []
        );
        $this->assertTrue($eventStream->isEmpty());
    }

    public function test_that_is_empty_returns_false_when_not_empty()
    {
        $eventStream = new EventStream(
            $this->userId,
            $this->userType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertFalse($eventStream->isEmpty());
    }

    public function test_that_it_is_countable()
    {
        $eventStream = new EventStream(
            $this->userId,
            $this->userType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame(3, count($eventStream));
    }

    public function test_that_object_id_returns_expected_instance()
    {
        $eventStream = new EventStream(
            $this->userId,
            $this->userType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame('014ec11d-2f21-4d33-a624-5df1196a4f85', $eventStream->objectId()->toString());
    }

    public function test_that_object_type_returns_expected_instance()
    {
        $eventStream = new EventStream(
            $this->userId,
            $this->userType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame('Novuso.Test.Common.Doubles.User', $eventStream->objectType()->toString());
    }

    public function test_that_messages_returns_expected_messages()
    {
        $eventStream = new EventStream(
            $this->userId,
            $this->userType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $messages = $eventStream->messages();
        $this->assertSame('014ec11e-4343-49cd-9b7a-cdd4ced5cedc', $messages[0]->eventId()->toString());
    }

    public function test_that_committed_returns_expected_value()
    {
        $eventStream = new EventStream(
            $this->userId,
            $this->userType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame($this->committed, $eventStream->committed());
    }

    public function test_that_version_returns_expected_value()
    {
        $eventStream = new EventStream(
            $this->userId,
            $this->userType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame($this->version, $eventStream->version());
    }

    protected function getEventMessages()
    {
        $eventId = EventId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $dateTime = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $eventData = new UserRegisteredEvent('Leeroy Jenkins', 'ljenkins');
        $sequence = 4;
        $eventMessage1 = new EventMessage(
            $eventId,
            $this->userId,
            $this->userType,
            $dateTime,
            $this->metaData,
            $eventData,
            $sequence
        );

        $eventId = EventId::fromString('014ec11f-317e-475a-9d5f-b8ae6c20aab1');
        $dateTime = DateTime::fromString('2015-01-02T10:34:12.672291[America/Chicago]');
        $eventData = new UserRegisteredEvent('John Smith', 'jsmith');
        $sequence = 5;
        $eventMessage2 = new EventMessage(
            $eventId,
            $this->userId,
            $this->userType,
            $dateTime,
            $this->metaData,
            $eventData,
            $sequence
        );

        $eventId = EventId::fromString('014ec170-e319-4875-937d-ead12b479682');
        $dateTime = DateTime::fromString('2015-01-05T14:03:31.245115[America/Chicago]');
        $eventData = new UserRegisteredEvent('George Fox', 'gfox');
        $sequence = 6;
        $eventMessage3 = new EventMessage(
            $eventId,
            $this->userId,
            $this->userType,
            $dateTime,
            $this->metaData,
            $eventData,
            $sequence
        );

        return [$eventMessage1, $eventMessage2, $eventMessage3];
    }
}
