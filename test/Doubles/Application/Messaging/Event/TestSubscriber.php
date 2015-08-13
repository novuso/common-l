<?php

namespace Novuso\Test\Common\Doubles\Application\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\EventMessage;
use Novuso\Common\Domain\Messaging\Event\Subscriber;
use Novuso\System\Utility\ClassName;
use Novuso\Test\Common\Doubles\Domain\Messaging\Event\ThingHappenedEvent;
use Novuso\Test\Common\Doubles\Domain\Model\DescriptionChangedEvent;
use Novuso\Test\Common\Doubles\Domain\Model\TaskCreatedEvent;

class TestSubscriber implements Subscriber
{
    protected $thingHappened = false;
    protected $taskId = '';
    protected $description = '';

    public static function eventRegistration()
    {
        $thingHappened = ClassName::underscore(ThingHappenedEvent::class);
        $taskCreated = ClassName::underscore(TaskCreatedEvent::class);
        $descriptionChanged = ClassName::underscore(DescriptionChangedEvent::class);

        return [
            $thingHappened      => 'onThingHappened',
            $taskCreated        => ['onTaskCreated', 10],
            $descriptionChanged => [
                ['onDescriptionChangedSecond', 10],
                ['onDescriptionChangedThird', 5],
                ['onDescriptionChangedFirst', 100]
            ]
        ];
    }

    public function onThingHappened(EventMessage $message)
    {
        $this->thingHappened = true;
    }

    public function onTaskCreated(EventMessage $message)
    {
        $domainEvent = $message->payload();
        $this->taskId = $domainEvent->taskId()->toString();
    }

    public function onDescriptionChangedFirst(EventMessage $message)
    {
        $metaData = $message->metaData();
        $metaData->set('first', true);
    }

    public function onDescriptionChangedSecond(EventMessage $message)
    {
        $metaData = $message->metaData();
        $metaData->set('second', true);
    }

    public function onDescriptionChangedThird(EventMessage $message)
    {
        $metaData = $message->metaData();
        $metaData->set('third', true);
        $domainEvent = $message->payload();
        $this->description = $domainEvent->description();
    }

    public function thingHappened()
    {
        return $this->thingHappened;
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
