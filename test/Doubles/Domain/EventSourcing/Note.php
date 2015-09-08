<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\EventSourcedDomainEntity;

final class Note extends EventSourcedDomainEntity
{
    private $id;
    private $text;

    private function __construct(NoteId $id, $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    public static function write(NoteId $id, $text)
    {
        return new self($id, $text);
    }

    public function id()
    {
        return $this->id;
    }

    // exposing to test exception
    public function aggregateRoot()
    {
        return $this->getAggregateRoot();
    }
}
