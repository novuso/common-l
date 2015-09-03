<?php

namespace Novuso\Test\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\DomainEventMessage;
use Novuso\Common\Domain\Messaging\Event\DomainEventStream;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\Domain\Messaging\Event\ThingHappenedEvent;
use Novuso\Test\Common\Doubles\Domain\Model\Thing;
use Novuso\Test\Common\Doubles\Domain\Model\ThingId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Messaging\Event\DomainEventStream
 */
class EventStreamTest extends PHPUnit_Framework_TestCase
{
    protected $thingId;
    protected $thingType;
    protected $metaData;
    protected $committed;
    protected $version;

    public function setUp()
    {
        $this->thingId = ThingId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $this->thingType = Type::create(Thing::class);
        $this->metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $this->committed = 3;
        $this->version = 6;
    }

    public function test_that_is_empty_returns_true_when_empty()
    {
        $eventStream = new DomainEventStream(
            $this->thingId,
            $this->thingType,
            $this->committed,
            $this->committed,
            []
        );
        $this->assertTrue($eventStream->isEmpty());
    }

    public function test_that_is_empty_returns_false_when_not_empty()
    {
        $eventStream = new DomainEventStream(
            $this->thingId,
            $this->thingType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertFalse($eventStream->isEmpty());
    }

    public function test_that_it_is_countable()
    {
        $eventStream = new DomainEventStream(
            $this->thingId,
            $this->thingType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame(3, count($eventStream));
    }

    public function test_that_aggregate_id_returns_expected_instance()
    {
        $eventStream = new DomainEventStream(
            $this->thingId,
            $this->thingType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame('014ec11d-2f21-4d33-a624-5df1196a4f85', $eventStream->aggregateId()->toString());
    }

    public function test_that_aggregate_type_returns_expected_instance()
    {
        $eventStream = new DomainEventStream(
            $this->thingId,
            $this->thingType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame('Novuso.Test.Common.Doubles.Domain.Model.Thing', $eventStream->aggregateType()->toString());
    }

    public function test_that_committed_returns_expected_value()
    {
        $eventStream = new DomainEventStream(
            $this->thingId,
            $this->thingType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame($this->committed, $eventStream->committed());
    }

    public function test_that_version_returns_expected_value()
    {
        $eventStream = new DomainEventStream(
            $this->thingId,
            $this->thingType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $this->assertSame($this->version, $eventStream->version());
    }

    public function test_that_it_is_json_encodable()
    {
        $eventStream = new DomainEventStream(
            $this->thingId,
            $this->thingType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $data = ['stream' => $eventStream];
        $expected = '{"stream":{"id":"014ec11d-2f21-4d33-a624-5df1196a4f85",'
            .'"type":"Novuso.Test.Common.Doubles.Domain.Model.Thing",'
            .'"committed":3,"version":6,"messages":['
            .'{"message_id":"014ec11e-4343-49cd-9b7a-cdd4ced5cedc",'
            .'"timestamp":"2015-01-01T13:12:31.045234[America/Chicago]",'
            .'"event_type":"Novuso.Test.Common.Doubles.Domain.Messaging.Event.ThingHappenedEvent",'
            .'"event_data":{"foo":"Leeroy Jenkins","bar":"ljenkins"},'
            .'"meta_data":{"ip_address":"127.0.0.1"},'
            .'"aggregate_type":"Novuso.Test.Common.Doubles.Domain.Model.Thing",'
            .'"aggregate_id":"014ec11d-2f21-4d33-a624-5df1196a4f85","sequence":4},'
            .'{"message_id":"014ec11f-317e-475a-9d5f-b8ae6c20aab1",'
            .'"timestamp":"2015-01-02T10:34:12.672291[America/Chicago]",'
            .'"event_type":"Novuso.Test.Common.Doubles.Domain.Messaging.Event.ThingHappenedEvent",'
            .'"event_data":{"foo":"John Smith","bar":"jsmith"},'
            .'"meta_data":{"ip_address":"127.0.0.1"},'
            .'"aggregate_type":"Novuso.Test.Common.Doubles.Domain.Model.Thing",'
            .'"aggregate_id":"014ec11d-2f21-4d33-a624-5df1196a4f85","sequence":5},'
            .'{"message_id":"014ec170-e319-4875-937d-ead12b479682",'
            .'"timestamp":"2015-01-05T14:03:31.245115[America/Chicago]",'
            .'"event_type":"Novuso.Test.Common.Doubles.Domain.Messaging.Event.ThingHappenedEvent",'
            .'"event_data":{"foo":"George Fox","bar":"gfox"},'
            .'"meta_data":{"ip_address":"127.0.0.1"},'
            .'"aggregate_type":"Novuso.Test.Common.Doubles.Domain.Model.Thing",'
            .'"aggregate_id":"014ec11d-2f21-4d33-a624-5df1196a4f85","sequence":6}'
            .']}}';
        $this->assertSame($expected, json_encode($data, JSON_UNESCAPED_SLASHES));
    }

    public function test_that_it_is_traversable()
    {
        $eventStream = new DomainEventStream(
            $this->thingId,
            $this->thingType,
            $this->committed,
            $this->version,
            $this->getEventMessages()
        );
        $messages = [];
        foreach ($eventStream as $message) {
            $messages[] = $message;
        }
        $this->assertSame('014ec11e-4343-49cd-9b7a-cdd4ced5cedc', $messages[0]->messageId()->toString());
    }

    protected function getEventMessages()
    {
        $messageId = MessageId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $dateTime = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $payload = new ThingHappenedEvent('Leeroy Jenkins', 'ljenkins');
        $sequence = 4;
        $eventMessage1 = new DomainEventMessage(
            $this->thingId,
            $this->thingType,
            $messageId,
            $dateTime,
            $payload,
            $this->metaData,
            $sequence
        );

        $messageId = MessageId::fromString('014ec11f-317e-475a-9d5f-b8ae6c20aab1');
        $dateTime = DateTime::fromString('2015-01-02T10:34:12.672291[America/Chicago]');
        $payload = new ThingHappenedEvent('John Smith', 'jsmith');
        $sequence = 5;
        $eventMessage2 = new DomainEventMessage(
            $this->thingId,
            $this->thingType,
            $messageId,
            $dateTime,
            $payload,
            $this->metaData,
            $sequence
        );

        $messageId = MessageId::fromString('014ec170-e319-4875-937d-ead12b479682');
        $dateTime = DateTime::fromString('2015-01-05T14:03:31.245115[America/Chicago]');
        $payload = new ThingHappenedEvent('George Fox', 'gfox');
        $sequence = 6;
        $eventMessage3 = new DomainEventMessage(
            $this->thingId,
            $this->thingType,
            $messageId,
            $dateTime,
            $payload,
            $this->metaData,
            $sequence
        );

        return [$eventMessage1, $eventMessage2, $eventMessage3];
    }
}
