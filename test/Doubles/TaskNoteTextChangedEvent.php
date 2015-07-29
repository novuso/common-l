<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Event\DomainEvent;

final class TaskNoteTextChangedEvent implements DomainEvent
{
    protected $taskId;
    protected $noteId;
    protected $text;

    public function __construct(TaskId $taskId, NoteId $noteId, $text)
    {
        $this->taskId = $taskId;
        $this->noteId = $noteId;
        $this->text = (string) $text;
    }

    public static function deserialize(array $data)
    {
        $taskId = TaskId::fromString($data['task_id']);
        $noteId = NoteId::fromString($data['note_id']);
        $text = $data['text'];

        return new self($taskId, $noteId, $text);
    }

    public function serialize()
    {
        return [
            'task_id' => $this->taskId->toString(),
            'note_id' => $this->noteId->toString(),
            'text'    => $this->text
        ];
    }

    public function taskId()
    {
        return $this->taskId;
    }

    public function noteId()
    {
        return $this->noteId;
    }

    public function text()
    {
        return $this->text;
    }
}
