<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot;
use Novuso\Common\Domain\Messaging\Event\EventStream;

final class Document extends EventSourcedAggregateRoot
{
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
        $document->recordThat(new DocumentCreated($id));

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
        $this->recordThat(new NoteAdded($this->id, $noteId, $text));
    }

    protected function applyNoteAdded(NoteAdded $domainEvent)
    {
        $noteId = $domainEvent->noteId();
        $text = $domainEvent->noteText();
        $note = Note::write($noteId, $text);
        $this->notes[] = $note;
    }

    protected function childEntities()
    {
        return $this->notes;
    }
}
