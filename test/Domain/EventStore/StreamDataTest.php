<?php

namespace Novuso\Test\Common\Domain\EventStore;

use Novuso\Common\Domain\EventStore\StoredEvent;
use Novuso\Common\Domain\EventStore\StreamData;
use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\Domain\Messaging\Event\ThingHappenedEvent;
use Novuso\Test\Common\Doubles\Domain\Model\Thing;
use Novuso\Test\Common\Doubles\Domain\Model\ThingId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventStore\StreamData
 */
class StreamDataTest extends PHPUnit_Framework_TestCase
{
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
        $eventMessage = new EventMessage(
            $thingId,
            $thingType,
            $messageId,
            $timestamp,
            $payload,
            $metaData,
            $sequence
        );
        $this->storedEvent = new StoredEvent($eventMessage);
    }

    public function test_that_it_is_countable()
    {
        $streamData = new StreamData();
        $streamData->addEvents([$this->storedEvent]);
        $this->assertSame(1, count($streamData));
    }

    public function test_that_version_can_be_modified_externally()
    {
        $streamData = new StreamData();
        $streamData->setVersion(0);
        $this->assertSame(0, $streamData->getVersion());
    }

    public function test_that_get_events_returns_stored_event_instances()
    {
        $streamData = new StreamData();
        $streamData->addEvents([$this->storedEvent]);
        $storedEvents = $streamData->getEvents();
        $this->assertSame($storedEvents[0], $this->storedEvent);
    }
}
