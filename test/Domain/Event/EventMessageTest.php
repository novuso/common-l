<?php

namespace Novuso\Test\Common\Domain\Event;

use Novuso\Common\Domain\Event\EventId;
use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\MetaData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\User;
use Novuso\Test\Common\Doubles\UserId;
use Novuso\Test\Common\Doubles\UserRegisteredEvent;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Event\EventMessage
 */
class EventMessageTest extends PHPUnit_Framework_TestCase
{
    protected $eventMessage;

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
    }

    public function test_that_it_is_serializable()
    {
        $serializer = new JsonSerializer();
        $string = $serializer->serialize($this->eventMessage);
        $object = $serializer->deserialize($string);
        $this->assertTrue($object->equals($this->eventMessage));
    }

    public function test_that_event_id_returns_expected_instance()
    {
        $eventId = $this->eventMessage->eventId();
        $this->assertSame('014ec11e-4343-49cd-9b7a-cdd4ced5cedc', $eventId->toString());
    }

    public function test_that_event_type_returns_expected_instance()
    {
        $eventType = $this->eventMessage->eventType();
        $this->assertSame('Novuso.Test.Common.Doubles.UserRegisteredEvent', $eventType->toString());
    }

    public function test_that_object_id_returns_expected_instance()
    {
        $objectId = $this->eventMessage->objectId();
        $this->assertSame('014ec11d-2f21-4d33-a624-5df1196a4f85', $objectId->toString());
    }

    public function test_that_object_id_type_returns_expected_instance()
    {
        $objectIdType = $this->eventMessage->objectIdType();
        $this->assertSame('Novuso.Test.Common.Doubles.UserId', $objectIdType->toString());
    }

    public function test_that_object_type_returns_expected_instance()
    {
        $objectType = $this->eventMessage->objectType();
        $this->assertSame('Novuso.Test.Common.Doubles.User', $objectType->toString());
    }

    public function test_that_date_time_returns_expected_instance()
    {
        $dateTime = $this->eventMessage->dateTime();
        $this->assertSame('2015-01-01T13:12:31.045234[America/Chicago]', $dateTime->toString());
    }

    public function test_that_meta_data_returns_expected_instance()
    {
        $metaData = $this->eventMessage->metaData();
        $this->assertSame('127.0.0.1', $metaData->get('ip_address'));
    }

    public function test_that_domain_event_returns_expected_instance()
    {
        $eventData = $this->eventMessage->eventData();
        $this->assertSame('Leeroy Jenkins', $eventData->fullName());
    }

    public function test_that_sequence_returns_expected_value()
    {
        $this->assertSame(0, $this->eventMessage->sequence());
    }

    public function test_that_to_string_returns_expected_string()
    {
        $expected = $this->getToStringJson();
        $this->assertSame($expected, $this->eventMessage->toString());
    }

    public function test_that_string_cast_returns_expected_string()
    {
        $expected = $this->getToStringJson();
        $this->assertSame($expected, (string) $this->eventMessage);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $this->assertSame(0, $this->eventMessage->compareTo($this->eventMessage));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $serializer = new JsonSerializer();
        $eventMessage = $serializer->deserialize($serializer->serialize($this->eventMessage));
        $this->assertSame(0, $this->eventMessage->compareTo($eventMessage));
    }

    public function test_that_compare_to_returns_one_for_greater_instance()
    {
        $next = $this->getNextEvent();
        $this->assertSame(1, $next->compareTo($this->eventMessage));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_instance()
    {
        $next = $this->getNextEvent();
        $this->assertSame(-1, $this->eventMessage->compareTo($next));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $this->assertTrue($this->eventMessage->equals($this->eventMessage));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $serializer = new JsonSerializer();
        $eventMessage = $serializer->deserialize($serializer->serialize($this->eventMessage));
        $this->assertTrue($this->eventMessage->equals($eventMessage));
    }

    public function test_that_equals_returns_false_for_invalid_value()
    {
        $this->assertFalse($this->eventMessage->equals('014ec11e434349cd9b7acdd4ced5cedc'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $next = $this->getNextEvent();
        $this->assertFalse($this->eventMessage->equals($next));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $this->assertSame('014ec11e434349cd9b7acdd4ced5cedc', $this->eventMessage->hashValue());
    }

    protected function getNextEvent()
    {
        $eventId = EventId::fromString('014ec11f-317e-475a-9d5f-b8ae6c20aab1');
        $userId = UserId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $userType = Type::create(User::class);
        $dateTime = DateTime::fromString('2015-01-02T10:34:12.672291[America/Chicago]');
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $eventData = new UserRegisteredEvent('John Smith', 'jsmith');
        $sequence = 1;

        return new EventMessage(
            $eventId,
            $userId,
            $userType,
            $dateTime,
            $metaData,
            $eventData,
            $sequence
        );
    }

    protected function getToStringJson()
    {
        return <<<EOT
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
    }
}
