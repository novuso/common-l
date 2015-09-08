<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot;
use Novuso\Common\Domain\Messaging\Event\EventStream;

final class Person extends EventSourcedAggregateRoot
{
    private $id;
    private $name;

    private function __construct(PersonId $id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function register($name)
    {
        $id = PersonId::generate();
        $person = new self($id, $name);
        $person->raiseEvent(new PersonRegistered($id, $name));

        return $person;
    }

    public static function reconstitute(EventStream $eventStream)
    {
        $id = $eventStream->aggregateId();
        $person = new self($id);
        $person->initializeFromEventStream($eventStream);

        return $person;
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }
}
