<?php

namespace Novuso\Test\Common\Doubles\Domain\Model;

use Novuso\Common\Domain\Model\AggregateRoot;

final class Task extends AggregateRoot
{
    private $id;
    private $description;

    private function __construct(TaskId $id, $description)
    {
        $this->id = $id;
        $this->description = (string) $description;
    }

    public static function create($description)
    {
        $id = TaskId::generate();
        $task = new self($id, $description);

        $task->recordThat(new TaskCreatedEvent($id, $description));

        return $task;
    }

    public static function reconstitute(TaskId $id, $description, $version)
    {
        $task = new self($id, $description);
        $task->initializeCommittedVersion($version);

        return $task;
    }

    public function id()
    {
        return $this->id;
    }

    public function description()
    {
        return $this->description;
    }

    public function changeDescription($description)
    {
        $this->description = (string) $description;
        $this->recordThat(new DescriptionChangedEvent($this->id, $this->description));
    }

    /**
     * Made public to allow triggering exception
     */
    public function initializeVersion($committedVersion)
    {
        $this->initializeCommittedVersion($committedVersion);
    }
}
