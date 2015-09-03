<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Model\Serialization;

final class NoteAdded implements DomainEvent
{
    use Serialization;

    private $documentId;
    private $noteId;
    private $noteText;

    public function __construct(DocumentId $documentId, NoteId $noteId, $noteText)
    {
        $this->documentId = $documentId;
        $this->noteId = $noteId;
        $this->noteText = $noteText;
    }

    public function documentId()
    {
        return $this->documentId;
    }

    public function noteId()
    {
        return $this->noteId;
    }

    public function noteText()
    {
        return $this->noteText;
    }

    public function jsonSerialize()
    {
        return [
            'document_id' => $this->documentId,
            'note_id'     => $this->noteId,
            'note_text'   => $this->noteText
        ];
    }
}
