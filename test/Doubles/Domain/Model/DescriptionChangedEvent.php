<?php

namespace Novuso\Test\Common\Doubles\Domain\Model;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;

final class DescriptionChangedEvent implements DomainEvent
{
    protected $taskId;
    protected $description;

    public function __construct(TaskId $taskId, $description)
    {
        $this->taskId = $taskId;
        $this->description = (string) $description;
    }

    public static function deserialize(array $data)
    {
        $taskId = TaskId::fromString($data['task_id']);
        $description = $data['description'];

        return new self($taskId, $description);
    }

    public function serialize()
    {
        return [
            'task_id'     => $this->taskId->toString(),
            'description' => $this->description
        ];
    }

    public function taskId()
    {
        return $this->taskId;
    }

    public function description()
    {
        return $this->description;
    }
}