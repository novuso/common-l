<?php

namespace Novuso\Test\Common\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\Event\InMemoryDispatcher;
use Novuso\Common\Domain\Messaging\Event\DomainEventMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\Application\Messaging\Event\EventLogSubscriber;
use Novuso\Test\Common\Doubles\Application\Messaging\Event\TestSubscriber;
use Novuso\Test\Common\Doubles\Domain\Messaging\Event\ThingHappenedEvent;
use Novuso\Test\Common\Doubles\Domain\Model\Thing;
use Novuso\Test\Common\Doubles\Domain\Model\ThingId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Event\InMemoryDispatcher
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
        $thingId = ThingId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $thingType = Type::create(Thing::class);
        $messageId = MessageId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $timestamp = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $payload = new ThingHappenedEvent('foo', 'bar');
        $metaData = new MetaData();
        $sequence = 0;
        $eventMessage = new DomainEventMessage(
            $thingId,
            $thingType,
            $messageId,
            $timestamp,
            $payload,
            $metaData,
            $sequence
        );
        $this->dispatcher->dispatch($eventMessage);
        $this->assertTrue($subscriber->thingHappened());
    }

    public function test_that_detached_subscriber_is_not_called()
    {
        $subscriber = new TestSubscriber();
        $this->dispatcher->attach($subscriber);
        $thingId = ThingId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $thingType = Type::create(Thing::class);
        $messageId = MessageId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $timestamp = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $payload = new ThingHappenedEvent('foo', 'bar');
        $metaData = new MetaData();
        $sequence = 0;
        $eventMessage = new DomainEventMessage(
            $thingId,
            $thingType,
            $messageId,
            $timestamp,
            $payload,
            $metaData,
            $sequence
        );
        $this->dispatcher->detach($subscriber);
        $this->dispatcher->dispatch($eventMessage);
        $this->assertFalse($subscriber->thingHappened());
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
        $thingId = ThingId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $thingType = Type::create(Thing::class);
        $messageId = MessageId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $timestamp = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $payload = new ThingHappenedEvent('foo', 'bar');
        $metaData = new MetaData();
        $sequence = 0;
        $eventMessage = new DomainEventMessage(
            $thingId,
            $thingType,
            $messageId,
            $timestamp,
            $payload,
            $metaData,
            $sequence
        );
        $this->dispatcher->dispatch($eventMessage);
        $logs = $subscriber->getLogs();
        $expected = '{"message_id":"014ec11e-4343-49cd-9b7a-cdd4ced5cedc",'
            .'"timestamp":"2015-01-01T13:12:31.045234[America/Chicago]",'
            .'"event_type":"Novuso.Test.Common.Doubles.Domain.Messaging.Event.ThingHappenedEvent",'
            .'"event_data":{"foo":"foo","bar":"bar"},"meta_data":[],'
            .'"aggregate_type":"Novuso.Test.Common.Doubles.Domain.Model.Thing",'
            .'"aggregate_id":"014ec11d-2f21-4d33-a624-5df1196a4f85","sequence":0}';
        $this->assertSame($expected, $logs[0]);
    }
}
