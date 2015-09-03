<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Model\Serialization;

final class DocumentCreated implements DomainEvent
{
    use Serialization;

    private $documentId;

    public function __construct(DocumentId $documentId)
    {
        $this->documentId = $documentId;
    }

    public function documentId()
    {
        return $this->documentId;
    }

    public function jsonSerialize()
    {
        return ['document_id' => $this->documentId];
    }
}
