<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Event\EventMessage;
use Novuso\Common\Domain\Event\Subscriber;
use Novuso\System\Utility\ClassName;

class TestSubscriber implements Subscriber
{
    protected $welcomeSent = false;

    public static function eventRegistration()
    {
        $userRegistered = ClassName::underscore(UserRegisteredEvent::class);
        $taskCreated = ClassName::underscore(TaskCreatedEvent::class);
        $descriptionChanged = ClassName::underscore(TaskDescriptionChangedEvent::class);

        return [
            $userRegistered        => 'onUserRegisteredSingle',
            $taskCreated           => ['onUserRegisteredLate', -10],
            $descriptionChanged    => [
                ['onUserRegisteredSecond', 10],
                ['onUserRegisteredThird', 5],
                ['onUserRegisteredFirst', 100]
            ]
        ];
    }

    public function onUserRegisteredSingle(EventMessage $message)
    {
        $this->welcomeSent = true;
    }

    public function onUserRegisteredLate(EventMessage $message)
    {
        $metaData = $message->metaData();
        $metaData->set('late', true);
    }

    public function onUserRegisteredFirst(EventMessage $message)
    {
        $metaData = $message->metaData();
        $metaData->set('first', true);
    }

    public function onUserRegisteredSecond(EventMessage $message)
    {
        $metaData = $message->metaData();
        $metaData->set('second', true);
    }

    public function onUserRegisteredThird(EventMessage $message)
    {
        $metaData = $message->metaData();
        $metaData->set('third', true);
    }

    public function welcomeSent()
    {
        return $this->welcomeSent;
    }
}
