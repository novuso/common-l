<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot;

final class Task extends EventSourcedAggregateRoot
{
    protected $id;
    protected $description;
    protected $notes = [];

    protected function __construct(TaskId $taskId, $description)
    {
        $this->id = $taskId;
        $this->description = (string) $description;
    }

    public static function create($description)
    {
        $taskId = TaskId::generate();
        $task = new self($taskId, $description);

        $task->recordEvent(new TaskCreatedEvent($taskId, $description));

        return $task;
    }

    public function changeDescription($description)
    {
        $this->recordEvent(new TaskDescriptionChangedEvent($this->id, $description));
    }

    public function attachNote($name, $text)
    {
        $note = Note::create($this, $name, $text);

        $this->recordEvent(new TaskNoteAttachedEvent($this->id, $note->id(), $text));
    }

    public function changeNote($name, $text)
    {
        if (!isset($this->notes[$name])) {
            throw \InvalidArgumentException('Invalid note name');
        }

        $note = $this->notes[$name];

        $this->recordEvent(new TaskNoteTextChangedEvent($this->id, $note->id(), $text));
    }

    public function id()
    {
        return $this->id;
    }

    public function description()
    {
        return $this->description;
    }

    public function readNote($name)
    {
        if (!isset($this->notes[$name])) {
            throw \InvalidArgumentException('Invalid note name');
        }

        return $this->notes[$name]->text();
    }

    /**
     * Made public to allow triggering exception
     */
    public function initializeCommittedVersion($committedVersion)
    {
        parent::initializeCommittedVersion($committedVersion);
    }

    protected function applyTaskCreatedEvent(TaskCreatedEvent $event)
    {
        $this->id = $event->taskId();
        $this->description = $event->description();
    }

    protected function applyTaskDescriptionChangedEvent(TaskDescriptionChangedEvent $event)
    {
        $this->description = $event->description();
    }

    protected function applyTaskNoteAttachedEvent(TaskNoteAttachedEvent $event)
    {
        $name = $event->noteId()->toString();
        $text = $event->text();
        $note = Note::create($this, $name, $text);

        $this->notes[$name] = $note;
    }

    protected function childEntities()
    {
        return $this->notes;
    }
}
