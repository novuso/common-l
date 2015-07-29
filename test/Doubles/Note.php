<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\EventSourcing\EventSourcedDomainEntity;

final class Note extends EventSourcedDomainEntity
{
    protected $id;
    protected $text;
    protected $task;

    protected function __construct(Task $task, NoteId $id, $text)
    {
        $this->id = $id;
        $this->text = (string) $text;
        $this->registerAggregateRoot($task);
    }

    public static function create(Task $task, $name, $text)
    {
        $id = NoteId::fromString($name);
        $note = new self($task, $id, $text);

        return $note;
    }

    public function id()
    {
        return $this->id;
    }

    public function text()
    {
        return $this->text;
    }

    protected function applyTaskNoteTextChangedEvent(TaskNoteTextChangedEvent $event)
    {
        $this->text = $event->text();
    }
}
