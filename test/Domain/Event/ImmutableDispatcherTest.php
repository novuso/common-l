<?php

namespace Novuso\Test\Common\Domain\Event;

use Novuso\Common\Domain\Event\EventId;
use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\ImmutableDispatcher;
use Novuso\Common\Domain\Event\InMemoryDispatcher;
use Novuso\Common\Domain\Event\MetaData;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\User;
use Novuso\Test\Common\Doubles\UserId;
use Novuso\Test\Common\Doubles\UserRegisteredEvent;
use Novuso\Test\Common\Doubles\TestSubscriber;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Event\ImmutableDispatcher
 */
class ImmutableDispatcherTest extends PHPUnit_Framework_TestCase
{
    protected $dispatcher;
    protected $subscriber;

    public function setUp()
    {
        $dispatcher = new InMemoryDispatcher();
        $this->subscriber = new TestSubscriber();
        $dispatcher->attach($this->subscriber);
        $this->dispatcher = new ImmutableDispatcher($dispatcher);
    }

    public function test_that_event_is_dispatched_to_handler()
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
        $this->dispatcher->dispatch($eventMessage);
        $this->assertTrue($this->subscriber->welcomeSent());
    }

    public function test_that_get_handlers_returns_expected_event_types()
    {
        $handlers = $this->dispatcher->getHandlers();
        // three event types, not just three handlers
        $this->assertSame(3, count($handlers));
    }

    public function test_that_has_handlers_returns_true_for_registered_handlers()
    {
        $this->assertTrue($this->dispatcher->hasHandlers());
    }

    /**
     * @expectedException Novuso\System\Exception\ImmutableException
     */
    public function test_that_attach_throws_exception_when_called()
    {
        $this->dispatcher->attach($this->subscriber);
    }

    /**
     * @expectedException Novuso\System\Exception\ImmutableException
     */
    public function test_that_detach_throws_exception_when_called()
    {
        $this->dispatcher->detach($this->subscriber);
    }

    /**
     * @expectedException Novuso\System\Exception\ImmutableException
     */
    public function test_that_add_handler_throws_exception_when_called()
    {
        $this->dispatcher->addHandler('foo', function () {});
    }

    /**
     * @expectedException Novuso\System\Exception\ImmutableException
     */
    public function test_that_remove_handler_throws_exception_when_called()
    {
        $this->dispatcher->removeHandler('foo', function () {});
    }
}
