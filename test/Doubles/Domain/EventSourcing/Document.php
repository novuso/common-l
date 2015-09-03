<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\AggregateEventSourcing;
use Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot;
use Novuso\Common\Domain\Messaging\Event\EventStream;
use Novuso\Common\Domain\Model\Identity;

final class Document implements EventSourcedAggregateRoot
{
    use AggregateEventSourcing;
    use Identity;

    private $id;
    private $notes;

    private function __construct(DocumentId $id)
    {
        $this->id = $id;
        $this->notes = [];
    }

    public static function create()
    {
        $id = DocumentId::generate();
        $document = new self($id);
        $document->apply(new DocumentCreated($id));

        return $document;
    }

    public static function reconstitute(EventStream $eventStream)
    {
        $id = $eventStream->aggregateId();
        $document = new self($id);
        $document->initializeFromEventStream($eventStream);

        return $document;
    }

    public function id()
    {
        return $this->id;
    }

    public function notes()
    {
        return $this->notes;
    }

    public function addNote($text)
    {
        $noteId = NoteId::generate();
        $this->apply(new NoteAdded($this->id, $noteId, $text));
    }

    private function applyDocumentCreated(DocumentCreated $domainEvent)
    {
        $this->id = $domainEvent->documentId();
    }

    private function applyNoteAdded(NoteAdded $domainEvent)
    {
        $noteId = $domainEvent->noteId();
        $text = $domainEvent->noteText();
        $note = Note::write($noteId, $text);
        $this->notes[] = $note;
    }

    private function childEntities()
    {
        return $this->notes;
    }
}
