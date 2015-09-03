<?php

namespace Novuso\Test\Common\Domain\EventStore;

use Novuso\Common\Domain\EventStore\StoredEvent;
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
 * @covers Novuso\Common\Domain\EventStore\StoredEvent
 */
class StoredEventTest extends PHPUnit_Framework_TestCase
{
    protected $eventMessage;
    protected $storedEvent;

    public function setUp()
    {
        $thingId = ThingId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $thingType = Type::create(Thing::class);
        $messageId = MessageId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $timestamp = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $payload = new ThingHappenedEvent('foo', 'bar');
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
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
        $this->storedEvent = new StoredEvent($this->eventMessage);
    }

    public function test_that_get_aggregate_id_returns_expected_value()
    {
        $expected = '014ec11d-2f21-4d33-a624-5df1196a4f85';
        $this->assertSame($expected, $this->storedEvent->getAggregateId());
    }

    public function test_that_get_aggregate_id_type_returns_expected_value()
    {
        $expected = 'Novuso.Test.Common.Doubles.Domain.Model.ThingId';
        $this->assertSame($expected, $this->storedEvent->getAggregateIdType());
    }

    public function test_that_get_aggregate_type_returns_expected_value()
    {
        $expected = 'Novuso.Test.Common.Doubles.Domain.Model.Thing';
        $this->assertSame($expected, $this->storedEvent->getAggregateType());
    }

    public function test_that_get_message_id_returns_expected_value()
    {
        $expected = '014ec11e-4343-49cd-9b7a-cdd4ced5cedc';
        $this->assertSame($expected, $this->storedEvent->getMessageId());
    }

    public function test_that_get_timestamp_returns_expected_value()
    {
        $expected = '2015-01-01T13:12:31.045234[America/Chicago]';
        $this->assertSame($expected, $this->storedEvent->getTimestamp());
    }

    public function test_that_get_payload_returns_expected_value()
    {
        $payload = unserialize($this->storedEvent->getPayload());
        $this->assertSame('{"foo":"foo","bar":"bar"}', json_encode($payload));
    }

    public function test_that_get_payload_type_returns_expected_value()
    {
        $expected = 'Novuso.Test.Common.Doubles.Domain.Messaging.Event.ThingHappenedEvent';
        $this->assertSame($expected, $this->storedEvent->getPayloadType());
    }

    public function test_that_get_meta_data_returns_expected_value()
    {
        $metaData = unserialize($this->storedEvent->getMetaData());
        $this->assertSame('{"ip_address":"127.0.0.1"}', json_encode($metaData));
    }

    public function test_that_get_sequence_returns_expected_value()
    {
        $expected = 0;
        $this->assertSame($expected, $this->storedEvent->getSequence());
    }

    public function test_that_to_event_message_returns_expected_instance()
    {
        $this->assertTrue($this->eventMessage->equals($this->storedEvent->toEventMessage()));
    }
}
