<?php

namespace Novuso\Test\Common\Domain\Model;

use Novuso\Test\Common\Doubles\Domain\Model\Task;
use Novuso\Test\Common\Doubles\Domain\Model\TaskId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\EventRecords
 */
class AggregateRootTest extends PHPUnit_Framework_TestCase
{
    public function test_that_it_records_events_as_expected()
    {
        $task = Task::create('Learn domain driven design');
        $desc = 'Develop a library to help with event sourcing';
        $task->changeDescription($desc);
        $commit = $task->committedVersion();
        $hasEvents = $task->hasRecordedEvents();
        $stream = $task->getRecordedEvents();
        $task->clearRecordedEvents();
        $newVersion = $task->committedVersion();
        $valid = true;
        if (!$hasEvents) {
            $valid = false;
        }
        if (count($stream) !== 2) {
            $valid = false;
        }
        if ($task->description() !== $desc) {
            $valid = false;
        }
        if ($commit !== null) {
            $valid = false;
        }
        if ($newVersion !== 1) {
            $valid = false;
        }
        $this->assertTrue($valid);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $task = Task::create('Go to the store');
        $this->assertSame(0, $task->compareTo($task));
    }

    public function test_that_compare_to_returns_zero_for_same_identity()
    {
        $task1 = Task::create('Go to the store');
        $task2 = Task::reconstitute($task1->id(), $task1->description(), 0);
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
        $task1 = Task::create('Go to the store');
        $task2 = Task::reconstitute($task1->id(), $task1->description(), 0);
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
        $id = TaskId::fromString('014f18df-9e1a-44de-9eee-d9f997491947');
        $task = Task::reconstitute($id, 'Go to the store', 0);
        $this->assertSame('014f18df9e1a44de9eeed9f997491947', $task->hashValue());
    }

    /**
     * @expectedException Novuso\System\Exception\OperationException
     */
    public function test_initialize_committed_version_throws_exception_with_invalid_call()
    {
        $task = Task::create('Go to the store');
        $task->initializeVersion(0);
    }
}
