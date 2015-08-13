<?php

namespace Novuso\Test\Common\Domain\EventStore;

use Novuso\Common\Domain\EventStore\InMemoryEventStore;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\Domain\Model\Task;
use Novuso\Test\Common\Doubles\Domain\Model\TaskId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventStore\InMemoryEventStore
 */
class InMemoryEventStoreTest extends PHPUnit_Framework_TestCase
{
    protected $store;

    public function setUp()
    {
        $this->store = new InMemoryEventStore();
    }

    public function test_that_it_keeps_events_over_multiple_operations()
    {
        $task = Task::create('First task description');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
        $task->clearRecordedEvents();

        $task->changeDescription('Updating the description');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
        $task->clearRecordedEvents();

        $task->changeDescription('Testing the event store');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
        $task->clearRecordedEvents();

        $idString = $task->id()->toString();

        $eventStream = $this->store->loadStream(
            TaskId::fromString($idString),
            Type::create(Task::class)
        );
        $eventMessages = [];
        foreach ($eventStream as $eventMessage) {
            $eventMessages[] = $eventMessage;
        }
        $taskCreated = 'Novuso.Test.Common.Doubles.Domain.Model.TaskCreatedEvent';
        $descChanged = 'Novuso.Test.Common.Doubles.Domain.Model.DescriptionChangedEvent';
        $this->assertTrue(
            $taskCreated === (string) $eventMessages[0]->payloadType()
                && $descChanged === (string) $eventMessages[1]->payloadType()
                && $descChanged === (string) $eventMessages[2]->payloadType()
        );
    }

    public function test_that_it_allows_adding_individual_events()
    {
        $task = Task::create('First task description');
        $stream = $task->getRecordedEvents();
        foreach ($stream as $event) {
            $this->store->appendEvent($event);
        }
        $task->clearRecordedEvents();

        $task->changeDescription('Updating the description');
        $stream = $task->getRecordedEvents();
        foreach ($stream as $event) {
            $this->store->appendEvent($event);
        }
        $task->clearRecordedEvents();

        $task->changeDescription('Testing the event store');
        $stream = $task->getRecordedEvents();
        foreach ($stream as $event) {
            $this->store->appendEvent($event);
        }
        $task->clearRecordedEvents();

        $idString = $task->id()->toString();

        $eventStream = $this->store->loadStream(
            TaskId::fromString($idString),
            Type::create(Task::class)
        );
        $eventMessages = [];
        foreach ($eventStream as $eventMessage) {
            $eventMessages[] = $eventMessage;
        }
        $taskCreated = 'Novuso.Test.Common.Doubles.Domain.Model.TaskCreatedEvent';
        $descChanged = 'Novuso.Test.Common.Doubles.Domain.Model.DescriptionChangedEvent';
        $this->assertTrue(
            $taskCreated === (string) $eventMessages[0]->payloadType()
                && $descChanged === (string) $eventMessages[1]->payloadType()
                && $descChanged === (string) $eventMessages[2]->payloadType()
        );
    }

    public function test_that_it_allows_loading_partial_streams()
    {
        $task = Task::create('First task description');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
        $task->clearRecordedEvents();

        $task->changeDescription('Updating the description');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
        $task->clearRecordedEvents();

        $task->changeDescription('Testing the event store');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
        $task->clearRecordedEvents();

        $idString = $task->id()->toString();

        $eventStream = $this->store->loadStream(
            TaskId::fromString($idString),
            Type::create(Task::class),
            1,
            1
        );
        $eventMessages = [];
        foreach ($eventStream as $eventMessage) {
            $eventMessages[] = $eventMessage;
        }
        $descChanged = 'Novuso.Test.Common.Doubles.Domain.Model.DescriptionChangedEvent';
        $this->assertTrue(
            count($eventMessages) === 1
            && $descChanged === (string) $eventMessages[0]->payloadType()
        );
    }

    /**
     * @expectedException Novuso\Common\Domain\EventStore\Exception\ConcurrencyException
     */
    public function test_that_append_stream_throws_exception_when_committed_version_does_not_match()
    {
        $task = Task::create('First task description');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
        $task->clearRecordedEvents();

        $task->changeDescription('Updating the description');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
        // simulating updates out of sync
        // $task->clearRecordedEvents();

        $task->changeDescription('Testing the event store');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
    }

    /**
     * @expectedException Novuso\Common\Domain\EventStore\Exception\ConcurrencyException
     */
    public function test_that_append_event_throws_exception_when_committed_version_does_not_match()
    {
        $task = Task::create('First task description');
        $stream = $task->getRecordedEvents();
        foreach ($stream as $event) {
            $this->store->appendEvent($event);
        }
        $task->clearRecordedEvents();

        $task->changeDescription('Updating the description');
        $stream = $task->getRecordedEvents();
        foreach ($stream as $event) {
            $this->store->appendEvent($event);
        }
        // simulating updates out of sync
        // $task->clearRecordedEvents();

        $task->changeDescription('Testing the event store');
        $stream = $task->getRecordedEvents();
        foreach ($stream as $event) {
            $this->store->appendEvent($event);
        }
    }

    /**
     * @expectedException Novuso\Common\Domain\EventStore\Exception\StreamNotFoundException
     */
    public function test_that_load_stream_throws_exception_when_type_does_not_have_a_stream()
    {
        $task = Task::create('First task description');
        $this->store->loadStream($task->id(), Type::create($task));
    }

    /**
     * @expectedException Novuso\Common\Domain\EventStore\Exception\StreamNotFoundException
     */
    public function test_that_load_stream_throws_exception_when_id_does_not_have_a_stream()
    {
        $task = Task::create('First task description');
        $stream = $task->getRecordedEvents();
        $this->store->appendStream($stream);
        $task->clearRecordedEvents();
        $other = Task::create('Another task');
        $this->store->loadStream($other->id(), Type::create($other));
    }
}
