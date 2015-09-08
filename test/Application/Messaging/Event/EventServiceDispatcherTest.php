<?php

namespace Novuso\Test\Common\Application\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\DomainEventMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\System\Utility\ClassName;
use Novuso\Test\Common\Doubles\Application\Messaging\Event\TestSubscriber;
use Novuso\Test\Common\Doubles\Domain\Messaging\Event\ThingHappenedEvent;
use Novuso\Test\Common\Doubles\Domain\Model\Thing;
use Novuso\Test\Common\Doubles\Domain\Model\ThingId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Messaging\Event\EventServiceDispatcher
 */
class EventServiceDispatcherTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = require __DIR__.'/Resources/container.php';
    }

    public function test_that_event_is_dispatched_to_attached_service()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
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
        $dispatcher->dispatch($eventMessage);
        $this->assertTrue($this->container->get('test.subscriber')->thingHappened());
    }

    public function test_that_get_handlers_returns_expected_value_for_all_events()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        // handlers registered for three different events
        $this->assertSame(3, count($dispatcher->getHandlers()));
    }

    public function test_that_get_handlers_returns_expected_value_for_event_type()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $eventType = ClassName::underscore(ThingHappenedEvent::class);
        // one handler registered for ThingHappenedEvent
        $this->assertSame(1, count($dispatcher->getHandlers($eventType)));
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers_all_events()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $this->assertTrue($dispatcher->hasHandlers());
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers_event_type()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $eventType = ClassName::underscore(ThingHappenedEvent::class);
        $this->assertTrue($dispatcher->hasHandlers($eventType));
    }

    public function test_that_has_handlers_returns_true_with_handlers_added_in_memory()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $eventType = ClassName::underscore(ThingHappenedEvent::class);
        $dispatcher->addHandler($eventType, function () {});
        $this->assertTrue($dispatcher->hasHandlers($eventType));
    }

    public function test_that_remove_handler_removes_handlers_as_expected()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $subscriber = $this->container->get('test.subscriber');
        $dispatcher->detach($subscriber);
        $this->assertFalse($dispatcher->hasHandlers());
    }

    public function test_that_event_is_dispatched_correctly_when_service_is_changed()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
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
        $dispatcher->dispatch($eventMessage);
        $this->container->service('test.subscriber', function () {
            return new TestSubscriber();
        });
        $subscriber = $this->container->get('test.subscriber');
        $dispatcher->dispatch($eventMessage);
        $this->assertTrue($subscriber->thingHappened());
    }
}
