<?php

namespace Novuso\Test\Common\Domain\EventSourcing;

use Novuso\Test\Common\Doubles\Domain\EventSourcing\Document;
use Novuso\Test\Common\Doubles\Domain\EventSourcing\Menu;
use Novuso\Test\Common\Doubles\Domain\EventSourcing\Note;
use Novuso\Test\Common\Doubles\Domain\EventSourcing\NoteId;
use Novuso\Test\Common\Doubles\Domain\EventSourcing\Person;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventSourcing\AggregateEventSourcing
 * @covers Novuso\Common\Domain\EventSourcing\EntityEventSourcing
 * @covers Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot
 * @covers Novuso\Common\Domain\EventSourcing\EventSourcedDomainEntity
 */
class EventSourcingTest extends PHPUnit_Framework_TestCase
{
    public function test_that_it_generates_events()
    {
        $person = Person::register('John');
        $stream = $person->getRecordedEvents();
        $person->clearRecordedEvents();
        $events = [];
        foreach ($stream as $message) {
            $events[] = $message->payload();
        }
        $this->assertTrue($person->id()->equals($events[0]->personId()));
    }

    public function test_that_it_has_correct_initial_state()
    {
        $menu = Menu::create('admin');
        $this->assertSame('admin', (string) $menu->name());
    }

    public function test_that_children_can_be_added()
    {
        $document = Document::create();
        $document->addNote('Note one');
        $document->addNote('Note two');
        $this->assertSame(2, count($document->notes()));
    }

    public function test_that_children_can_handle_events_recursively()
    {
        $menu = Menu::create('admin');
        $menu->addMenuItem('/admin', 'Dashboard');
        $menu->addMenuItem('/admin/users', 'Users');
        $items = $menu->items();
        $parentId = $items[0]->id()->toString();
        $itemId = $items[1]->id()->toString();
        $menu->moveMenuItem($itemId, $parentId);
        $this->assertSame($items[0], $items[1]->parent());
    }

    public function test_that_it_can_be_reconstituted_from_event_stream()
    {
        $menu = Menu::create('admin');
        $menu->addMenuItem('/admin', 'Dashboard');
        $menu->addMenuItem('/admin/users', 'Users');
        $items = $menu->items();
        $parentId = $items[0]->id()->toString();
        $itemId = $items[1]->id()->toString();
        $menu->moveMenuItem($itemId, $parentId);
        $menu->addMenuItem('/logout', 'Logout');
        $eventStream = $menu->getRecordedEvents();
        $menu->clearRecordedEvents();
        $menu = Menu::reconstitute($eventStream);
        $this->assertSame(3, count($menu->items()));
    }

    /**
     * @expectedException Novuso\Common\Domain\EventSourcing\Exception\RegisterAggregateException
     */
    public function test_that_register_aggregate_root_throws_exception_when_called_invalid()
    {
        $note = Note::write(NoteId::generate(), 'test');
        $doc1 = Document::create();
        $note->internalRegisterAggregateRoot($doc1);
        $doc2 = Document::create();
        $note->internalRegisterAggregateRoot($doc2);
    }

    /**
     * @expectedException Novuso\Common\Domain\EventSourcing\Exception\RegisterAggregateException
     */
    public function test_that_get_aggregate_root_throws_exception_without_aggregate_root()
    {
        $note = Note::write(NoteId::generate(), 'test');
        $note->aggregateRoot();
    }
}
