<?php

namespace Novuso\Test\Common\Doubles\Application\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\ViewModel;
use Novuso\Test\Common\Doubles\Domain\Model\Task;

final class TaskViewModel implements ViewModel
{
    private $id;
    private $description;

    private function __construct(Task $task)
    {
        $this->id = $task->id()->toString();
        $this->description = $task->description();
    }

    public static function fromTask(Task $task)
    {
        return new self($task);
    }

    public function id()
    {
        return $this->id;
    }

    public function description()
    {
        return $this->description;
    }

    public function toArray()
    {
        return [
            'id'          => $this->id,
            'description' => $this->description
        ];
    }
}
