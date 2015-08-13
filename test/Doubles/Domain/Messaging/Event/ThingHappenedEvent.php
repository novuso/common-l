<?php

namespace Novuso\Test\Common\Doubles\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;

final class ThingHappenedEvent implements DomainEvent
{
    protected $foo;
    protected $bar;

    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public static function deserialize(array $data)
    {
        return new self($data['foo'], $data['bar']);
    }

    public function serialize()
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar
        ];
    }

    public function foo()
    {
        return $this->foo;
    }

    public function bar()
    {
        return $this->bar;
    }
}
