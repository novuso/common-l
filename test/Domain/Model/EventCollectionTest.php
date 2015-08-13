<?php

namespace Novuso\Test\Common\Domain\Model;

use Novuso\Common\Domain\Model\EventCollection;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\Domain\Messaging\Event\ThingHappenedEvent;
use Novuso\Test\Common\Doubles\Domain\Model\Thing;
use Novuso\Test\Common\Doubles\Domain\Model\ThingId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\EventCollection
 */
class EventCollectionTest extends PHPUnit_Framework_TestCase
{
    protected $eventCollection;

    public function setUp()
    {
        $thingId = ThingId::fromString('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $thingType = Type::create(Thing::class);
        $this->eventCollection = new EventCollection($thingId, $thingType);
    }

    public function test_that_it_is_empty_by_default()
    {
        $this->assertTrue($this->eventCollection->isEmpty());
    }

    public function test_that_recording_events_affects_count()
    {
        $domainEvent = new ThingHappenedEvent('foo', 'bar');
        $this->eventCollection->record($domainEvent);
        $this->assertSame(1, count($this->eventCollection));
    }

    public function test_that_recording_events_does_not_affect_committed_sequence()
    {
        // assuming the last event committed had a sequence number of 12
        $this->eventCollection->initializeSequence(12);
        $domainEvent = new ThingHappenedEvent('foo', 'bar');
        $this->eventCollection->record($domainEvent);
        $this->assertSame(12, $this->eventCollection->committedSequence());
    }

    public function test_that_recording_events_affects_last_sequence()
    {
        // assuming the last event committed had a sequence number of 12
        $this->eventCollection->initializeSequence(12);
        $domainEvent = new ThingHappenedEvent('foo', 'bar');
        $this->eventCollection->record($domainEvent);
        $this->assertSame(13, $this->eventCollection->lastSequence());
    }

    public function test_that_commit_updates_the_committed_sequence()
    {
        // assuming the last event committed had a sequence number of 12
        $this->eventCollection->initializeSequence(12);
        $domainEvent = new ThingHappenedEvent('foo', 'bar');
        $this->eventCollection->record($domainEvent);
        $this->eventCollection->commit();
        $this->assertSame(13, $this->eventCollection->committedSequence());
    }

    public function test_that_commit_clears_event_messages()
    {
        $domainEvent = new ThingHappenedEvent('foo', 'bar');
        $this->eventCollection->record($domainEvent);
        $this->eventCollection->commit();
        $this->assertTrue($this->eventCollection->isEmpty());
    }

    public function test_that_stream_returns_expected_event_stream()
    {
        // assuming the last event committed had a sequence number of 3
        $this->eventCollection->initializeSequence(3);
        $this->eventCollection->record(new ThingHappenedEvent('foo', 'bar'));
        $this->eventCollection->record(new ThingHappenedEvent('baz', 'buz'));
        $stream = $this->eventCollection->stream();
        // commit after retrieving stream
        $this->eventCollection->commit();
        $this->assertTrue(
            $stream->committed() === 3
            && $stream->version() === 5
            && $this->eventCollection->committedSequence() === 5
        );
    }
}
