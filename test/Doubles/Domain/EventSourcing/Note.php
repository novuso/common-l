<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\EntityEventSourcing;
use Novuso\Common\Domain\EventSourcing\EventSourcedEntity;
use Novuso\Common\Domain\Model\Identity;

final class Note implements EventSourcedEntity
{
    use EntityEventSourcing;
    use Identity;

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
