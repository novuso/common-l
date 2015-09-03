<?php

namespace Novuso\Test\Common\Doubles\Application\Messaging\Command;

use Novuso\Common\Application\Messaging\Event\Dispatcher;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;
use Novuso\Test\Common\Doubles\Domain\Model\Task;

class CreateTaskHandler implements CommandHandler
{
    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function handle(Command $command)
    {
        $description = $command->description();
        $task = Task::create($description);
        // do something to persist the task
        $stream = $task->getRecordedEvents();
        $task->clearRecordedEvents();
        foreach ($stream as $message) {
            $this->dispatcher->dispatch($message);
        }
    }
}
