<?php

namespace Novuso\Test\Common\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\AggregateRootFactory;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\Task;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventSourcing\AggregateRootFactory
 */
class AggregateRootFactoryTest extends PHPUnit_Framework_TestCase
{
    public function test_that_reconstitute_returns_expected_instance()
    {
        $aggregate = Task::create('Develop an application with event sourcing');
        $aggregate->attachNote('store', 'Do not forget the event store');
        $aggregate->changeNote('store', 'Do not forget an in memory event store');
        $stream = $aggregate->getRecordedEvents();
        $aggregate->commitRecordedEvents();
        // build task from type and stream
        $factory = new AggregateRootFactory();
        $type = Type::create(Task::class);
        $task = $factory->reconstitute($type, $stream);
        $expected = 'Do not forget an in memory event store';
        $this->assertSame($expected, $task->readNote('store'));
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_reconstitute_throws_exception_for_invalid_type()
    {
        $aggregate = Task::create('Develop an application with event sourcing');
        $aggregate->attachNote('store', 'Do not forget the event store');
        $aggregate->changeNote('store', 'Do not forget an in memory event store');
        $stream = $aggregate->getRecordedEvents();
        $aggregate->commitRecordedEvents();
        // build task from type and stream
        $factory = new AggregateRootFactory();
        $type = Type::create('ArrayObject');
        $factory->reconstitute($type, $stream);
    }
}
