<?php

namespace Novuso\Test\Common\Doubles\Application\Messaging\Query;

use Exception;
use Novuso\Common\Application\Messaging\Event\Dispatcher;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\Test\Common\Doubles\Domain\Model\TaskId;

class GetTaskHandler implements QueryHandler
{
    protected $tasks;

    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }

    public function handle(Query $query)
    {
        $id = TaskId::fromString($query->id());

        foreach ($this->tasks as $task) {
            if ($task->id()->equals($id)) {
                return TaskViewModel::fromTask($task);
            }
        }

        throw new Exception('Task not found');
    }
}
