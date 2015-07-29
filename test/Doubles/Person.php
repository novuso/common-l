<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot;

final class Person extends EventSourcedAggregateRoot
{
    protected $id;
    protected $name;

    protected function __construct(PersonId $id, $name)
    {
        $this->id = $id;
        $this->name = (string) $name;
    }

    public static function register($name)
    {
        $id = PersonId::generate();
        $person = new self($id, $name);

        $person->recordEvent(new PersonRegisteredEvent($id, $name));

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

    protected function applyPersonRegisteredEvent(PersonRegisteredEvent $event)
    {
        $this->id = $event->personId();
        $this->name = $event->personName();
    }
}
