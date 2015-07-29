<?php

namespace Novuso\Test\Common\Domain\EventSourcing;

use Novuso\Common\Domain\Event\EventStream;
use Novuso\Test\Common\Doubles\Person;
use Novuso\Test\Common\Doubles\Task;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot
 * @covers Novuso\Common\Domain\EventSourcing\EventSourcedDomainEntity
 */
class EventSourcingTest extends PHPUnit_Framework_TestCase
{
    public function test_that_aggregate_root_reconsitutes_as_expected()
    {
        $aggregate = Person::register('Joe Smith');
        $stream = $aggregate->getRecordedEvents();
        $aggregate->commitRecordedEvents();
        $person = Person::reconstitute($stream);
        $this->assertSame('Joe Smith', $person->name());
    }

    public function test_that_aggregate_root_records_events_as_expected()
    {
        $task = Task::create('Develop an application with event sourcing');
        $desc = 'Develop a library to help with event sourcing';
        $task->changeDescription($desc);
        $commit = $task->committedVersion();
        $stream = $task->getRecordedEvents();
        $this->assertTrue(count($stream) === 2 && $task->description() === $desc && $commit === null);
        $task->commitRecordedEvents();
    }

    public function test_that_child_entities_are_called_to_apply_events()
    {
        $aggregate = Task::create('Develop an application with event sourcing');
        $aggregate->attachNote('store', 'Do not forget the event store');
        $aggregate->changeNote('store', 'Do not forget an in memory event store');
        $stream = $aggregate->getRecordedEvents();
        $aggregate->commitRecordedEvents();
        $task = Task::reconstitute($stream);
        $expected = 'Do not forget an in memory event store';
        $this->assertSame($expected, $task->readNote('store'));
    }

    public function test_that_methods_return_expected_when_event_sourced()
    {
        $task = Task::reconstitute($this->createEventStream());
        $commit = $task->committedVersion();
        $this->assertTrue(!$task->hasRecordedEvents() && $commit === 1);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $task = Task::create('Go to the store');
        $this->assertSame(0, $task->compareTo($task));
    }

    public function test_that_compare_to_returns_zero_for_same_identity()
    {
        $task1 = Task::reconstitute($this->createEventStream());
        $task2 = Task::reconstitute($this->createEventStream());
        $task2->changeDescription('Do something different');
        $this->assertSame(0, $task1->compareTo($task2));
    }

    public function test_that_compare_to_returns_non_zero_for_different_identity()
    {
        $task1 = Task::create('Go to the store');
        $task2 = Task::create('Go to the store');
        $this->assertFalse($task1->compareTo($task2) === 0);
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $task = Task::create('Go to the store');
        $this->assertTrue($task->equals($task));
    }

    public function test_that_equals_returns_true_for_same_identity()
    {
        $task1 = Task::reconstitute($this->createEventStream());
        $task2 = Task::reconstitute($this->createEventStream());
        $task2->changeDescription('Do something different');
        $this->assertTrue($task1->equals($task2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $task = Task::create('Go to the store');
        $this->assertFalse($task->equals('Go to the store'));
    }

    public function test_that_equals_returns_false_for_different_identity()
    {
        $task1 = Task::create('Go to the store');
        $task2 = Task::create('Go to the store');
        $this->assertFalse($task1->equals($task2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $task = Task::reconstitute($this->createEventStream());
        $this->assertSame('014ed18b75e04f278fede4d83fc00bed', $task->hashValue());
    }

    /**
     * @expectedException Novuso\System\Exception\OperationException
     */
    public function test_initialize_committed_version_throws_exception_with_invalid_call()
    {
        $task = Task::create('Go to the store');
        $task->initializeCommittedVersion(0);
    }

    protected function createEventStream()
    {
        return EventStream::deserialize([
            'object_id'   => [
                'type'       => 'Novuso.Test.Common.Doubles.TaskId',
                'identifier' => '014ed18b-75e0-4f27-8fed-e4d83fc00bed'
            ],
            'object_type' => 'Novuso.Test.Common.Doubles.Task',
            'committed'   => null,
            'version'     => 1,
            'messages'    => [
                [
                    'event_id'   => '014ed18b-761f-458f-9f57-6747a71b0016',
                    'date_time'  => '2015-07-27T22:04:14.719303[UTC]',
                    'meta_data'  => [],
                    'event_data' => [
                        'type' => 'Novuso.Test.Common.Doubles.TaskCreatedEvent',
                        'data' => [
                            'task_id'     => '014ed18b-75e0-4f27-8fed-e4d83fc00bed',
                            'description' => 'Develop an application with event sourcing'
                        ]
                    ],
                    'sequence'  => 0
                ],
                [
                    'event_id'   => '014ed18b-7631-4fb4-8162-a0341418a1c6',
                    'date_time'  => '2015-07-27T22:04:14.766566[UTC]',
                    'meta_data'  => [],
                    'event_data' => [
                        'type' => 'Novuso.Test.Common.Doubles.TaskDescriptionChangedEvent',
                        'data' => [
                            'task_id'     => '014ed18b-75e0-4f27-8fed-e4d83fc00bed',
                            'description' => 'Develop a library to help with event sourcing'
                        ]
                    ],
                    'sequence'  => 1
                ]
            ]
        ]);
    }
}
