<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot;

final class Task extends EventSourcedAggregateRoot
{
    protected $id;
    protected $description;

    protected function __construct(TaskId $taskId, $description)
    {
        $this->id = $taskId;
        $this->description = (string) $description;
    }

    public static function create($description)
    {
        $taskId = TaskId::generate();
        $task = new self($taskId, $description);

        $task->recordThat(new TaskCreatedEvent($taskId, $description));

        return $task;
    }

    public function changeDescription($description)
    {
        $this->recordThat(new TaskDescriptionChangedEvent($this->id, $description));
    }

    public function id()
    {
        return $this->id;
    }

    public function description()
    {
        return $this->description;
    }

    /**
     * Made public to allow triggering exception
     */
    public function initializeConcurrencyVersion($concurrencyVersion)
    {
        parent::initializeConcurrencyVersion($concurrencyVersion);
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
}
