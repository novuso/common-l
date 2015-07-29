<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Event\DomainEvent;

final class PersonRegisteredEvent implements DomainEvent
{
    protected $personId;
    protected $personName;

    public function __construct(PersonId $personId, $personName)
    {
        $this->personId = $personId;
        $this->personName = (string) $personName;
    }

    public static function deserialize(array $data)
    {
        $personId = PersonId::fromString($data['person_id']);
        $personName = $data['person_name'];

        return new self($personId, $personName);
    }

    public function serialize()
    {
        return [
            'person_id'   => $this->personId->toString(),
            'person_name' => $this->personName
        ];
    }

    public function personId()
    {
        return $this->personId;
    }

    public function personName()
    {
        return $this->personName;
    }
}
