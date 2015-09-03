<?php

namespace Novuso\Test\Common\Doubles\Domain\Model;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;

final class TaskCreatedEvent implements DomainEvent
{
    private $taskId;
    private $description;

    public function __construct(TaskId $taskId, $description)
    {
        $this->taskId = $taskId;
        $this->description = (string) $description;
    }

    public function taskId()
    {
        return $this->taskId;
    }

    public function description()
    {
        return $this->description;
    }

    public function jsonSerialize()
    {
        return [
            'task_id'     => $this->taskId,
            'description' => $this->description
        ];
    }

    public function serialize()
    {
        return serialize([
            'task_id'     => $this->taskId,
            'description' => $this->description
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $taskId = $data['task_id'];
        $description = $data['description'];
        $this->__construct($taskId, $description);
    }
}
