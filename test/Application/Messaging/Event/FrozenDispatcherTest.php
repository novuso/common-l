<?php

namespace Novuso\Test\Common\Application\Messaging\Event;

use Novuso\Common\Application\Messaging\Event\FrozenDispatcher;
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
 * @covers Novuso\Common\Application\Messaging\Event\FrozenDispatcher
 */
class FrozenDispatcherTest extends PHPUnit_Framework_TestCase
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
        $dispatcher = new FrozenDispatcher($dispatcher);
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
        $dispatcher = new FrozenDispatcher($dispatcher);
        // handlers registered for three different events
        $this->assertSame(3, count($dispatcher->getHandlers()));
    }

    public function test_that_get_handlers_returns_expected_value_for_event_type()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $dispatcher = new FrozenDispatcher($dispatcher);
        $eventType = ClassName::underscore(ThingHappenedEvent::class);
        // one handler registered for ThingHappenedEvent
        $this->assertSame(1, count($dispatcher->getHandlers($eventType)));
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers_all_events()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $dispatcher = new FrozenDispatcher($dispatcher);
        $this->assertTrue($dispatcher->hasHandlers());
    }

    public function test_that_has_handlers_returns_true_when_there_are_handlers_event_type()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $dispatcher = new FrozenDispatcher($dispatcher);
        $eventType = ClassName::underscore(ThingHappenedEvent::class);
        $this->assertTrue($dispatcher->hasHandlers($eventType));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Event\Exception\FrozenDispatcherException
     */
    public function test_that_attach_throws_exception_when_called()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $dispatcher = new FrozenDispatcher($dispatcher);
        $dispatcher->attach($this->container->get('test.subscriber'));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Event\Exception\FrozenDispatcherException
     */
    public function test_that_detach_throws_exception_when_called()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $dispatcher = new FrozenDispatcher($dispatcher);
        $dispatcher->detach($this->container->get('test.subscriber'));
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Event\Exception\FrozenDispatcherException
     */
    public function test_that_add_handler_throws_exception_when_called()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $dispatcher = new FrozenDispatcher($dispatcher);
        $eventType = ClassName::underscore(ThingHappenedEvent::class);
        $dispatcher->addHandler($eventType, function () {});
    }

    /**
     * @expectedException Novuso\Common\Application\Messaging\Event\Exception\FrozenDispatcherException
     */
    public function test_that_remove_handler_throws_exception_when_called()
    {
        $dispatcher = $this->container->get('event.dispatcher');
        $dispatcher->attachService('test.subscriber', TestSubscriber::class);
        $dispatcher = new FrozenDispatcher($dispatcher);
        $eventType = ClassName::underscore(ThingHappenedEvent::class);
        $dispatcher->removeHandler($eventType, function () {});
    }
}
