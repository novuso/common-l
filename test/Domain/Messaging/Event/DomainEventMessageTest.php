<?php

namespace Novuso\Test\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\DomainEventMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\Domain\Messaging\Event\ThingHappenedEvent;
use Novuso\Test\Common\Doubles\Domain\Model\Thing;
use Novuso\Test\Common\Doubles\Domain\Model\ThingId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Messaging\Event\DomainEventMessage
 */
class DomainEventMessageTest extends PHPUnit_Framework_TestCase
{
    protected $eventMessage;

    public function setUp()
    {
        $thingId = ThingId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $thingType = Type::create(Thing::class);
        $messageId = MessageId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $timestamp = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $payload = new ThingHappenedEvent('foo', 'bar');
        $metaData = new MetaData();
        $sequence = 0;
        $this->eventMessage = new DomainEventMessage(
            $thingId,
            $thingType,
            $messageId,
            $timestamp,
            $payload,
            $metaData,
            $sequence
        );
    }

    public function test_that_it_is_serializable()
    {
        $string = serialize($this->eventMessage);
        $object = unserialize($string);
        $this->assertTrue($object->equals($this->eventMessage));
    }

    public function test_that_aggregate_id_returns_expected_instance()
    {
        $aggregateId = $this->eventMessage->aggregateId();
        $this->assertSame('014ec11d-2f21-4d33-a624-5df1196a4f85', $aggregateId->toString());
    }

    public function test_that_aggregate_type_returns_expected_instance()
    {
        $aggregateType = $this->eventMessage->aggregateType();
        $this->assertSame('Novuso.Test.Common.Doubles.Domain.Model.Thing', $aggregateType->toString());
    }

    public function test_that_message_id_returns_expected_instance()
    {
        $messageId = $this->eventMessage->messageId();
        $this->assertSame('014ec11e-4343-49cd-9b7a-cdd4ced5cedc', $messageId->toString());
    }

    public function test_that_timestamp_returns_expected_instance()
    {
        $timestamp = $this->eventMessage->timestamp();
        $this->assertSame('2015-01-01T13:12:31.045234[America/Chicago]', $timestamp->toString());
    }

    public function test_that_payload_returns_expected_instance()
    {
        $payload = $this->eventMessage->payload();
        $this->assertTrue($payload->foo() === 'foo' && $payload->bar() === 'bar');
    }

    public function test_that_payload_type_returns_expected_instance()
    {
        $payloadType = $this->eventMessage->payloadType();
        $expected = 'Novuso.Test.Common.Doubles.Domain.Messaging.Event.ThingHappenedEvent';
        $this->assertSame($expected, $payloadType->toString());
    }

    public function test_that_meta_data_returns_expected_instance()
    {
        $metaData = $this->eventMessage->metaData();
        $this->assertTrue($metaData->isEmpty());
    }

    public function test_that_with_meta_data_returns_equal_instance_with_new_meta_data()
    {
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $eventMessage = $this->eventMessage->withMetaData($metaData);
        $this->assertTrue(
            $eventMessage->metaData()->get('ip_address') === '127.0.0.1'
            && $this->eventMessage->equals($eventMessage)
        );
    }

    public function test_that_merge_meta_data_returns_equal_instance_with_merged_meta_data()
    {
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $eventMessage = $this->eventMessage->withMetaData($metaData);
        $metaData = new MetaData(['format' => 'json']);
        $eventMessage = $eventMessage->mergeMetaData($metaData);
        $this->assertTrue(
            $eventMessage->metaData()->get('ip_address') === '127.0.0.1'
            && $eventMessage->metaData()->get('format') === 'json'
            && $this->eventMessage->equals($eventMessage)
        );
    }

    public function test_that_sequence_returns_expected_value()
    {
        $this->assertSame(0, $this->eventMessage->sequence());
    }

    public function test_that_to_string_returns_expected_value()
    {
        $expected = $this->getToStringJson();
        $this->assertSame($expected, $this->eventMessage->toString());
    }

    public function test_that_string_cast_returns_expected_value()
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
        $eventMessage = unserialize(serialize($this->eventMessage));
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
        $eventMessage = unserialize(serialize($this->eventMessage));
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
        $thingId = ThingId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $thingType = Type::create(Thing::class);
        $messageId = MessageId::fromString('014ec11f-317e-475a-9d5f-b8ae6c20aab1');
        $timestamp = DateTime::fromString('2015-01-02T10:34:12.672291[America/Chicago]');
        $metaData = new MetaData();
        $payload = new ThingHappenedEvent('foo', 'bar');
        $sequence = 1;

        return new DomainEventMessage(
            $thingId,
            $thingType,
            $messageId,
            $timestamp,
            $payload,
            $metaData,
            $sequence
        );
    }

    protected function getToStringJson()
    {
        return '{"message_id":"014ec11e-4343-49cd-9b7a-cdd4ced5cedc",'
            .'"timestamp":"2015-01-01T13:12:31.045234[America/Chicago]",'
            .'"event_type":"Novuso.Test.Common.Doubles.Domain.Messaging.Event.ThingHappenedEvent",'
            .'"event_data":{"foo":"foo","bar":"bar"},"meta_data":[],'
            .'"aggregate_type":"Novuso.Test.Common.Doubles.Domain.Model.Thing",'
            .'"aggregate_id":"014ec11d-2f21-4d33-a624-5df1196a4f85","sequence":0}';
    }
}
