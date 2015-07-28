<?php

namespace Novuso\Test\Common\Domain\EventStore;

use Novuso\Common\Domain\EventStore\InMemoryEventStore;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\Task;
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

    public function test_that_store_keeps_events_over_multiple_operations()
    {
        $task = Task::create('First task description');
        $stream = $task->extractRecordedEvents();
        $this->store->append($stream);
        $task->changeDescription('Updating the description');
        $stream = $task->extractRecordedEvents();
        $this->store->append($stream);
        $task->changeDescription('Testing the event store');
        $stream = $task->extractRecordedEvents();
        $this->store->append($stream);
        // load event history and build new aggregate
        $history = $this->store->load($task->id(), Type::create($task));
        $object = Task::reconstitute($history);
        $this->assertSame('Testing the event store', $object->description());
    }

    /**
     * @expectedException Novuso\Common\Domain\EventStore\Exception\ConcurrencyException
     */
    public function test_that_append_throws_exception_when_committed_version_does_not_match()
    {
        $task = Task::create('First task description');
        $stream = $task->extractRecordedEvents();
        $this->store->append($stream);
        $task->changeDescription('Updating the description');
        $stream = $task->extractRecordedEvents();
        // simulating updates out of sync
        // $this->store->append($stream);
        $task->changeDescription('Testing the event store');
        $stream = $task->extractRecordedEvents();
        $this->store->append($stream);
    }

    /**
     * @expectedException Novuso\Common\Domain\EventStore\Exception\StreamNotFoundException
     */
    public function test_that_load_throws_exception_when_type_does_not_have_a_stream()
    {
        $task = Task::create('First task description');
        $this->store->load($task->id(), Type::create($task));
    }

    /**
     * @expectedException Novuso\Common\Domain\EventStore\Exception\StreamNotFoundException
     */
    public function test_that_load_throws_exception_when_id_does_not_have_a_stream()
    {
        $task = Task::create('First task description');
        $stream = $task->extractRecordedEvents();
        $this->store->append($stream);
        $other = Task::create('Another task');
        $this->store->load($other->id(), Type::create($other));
    }
}
