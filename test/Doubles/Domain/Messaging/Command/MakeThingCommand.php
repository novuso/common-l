<?php

namespace Novuso\Test\Common\Doubles\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\Command;

final class MakeThingCommand implements Command
{
    private $foo;
    private $bar;

    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public function foo()
    {
        return $this->foo;
    }

    public function bar()
    {
        return $this->bar;
    }

    public function jsonSerialize()
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar
        ];
    }

    public function serialize()
    {
        return serialize([
            'foo' => $this->foo,
            'bar' => $this->bar
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->__construct($data['foo'], $data['bar']);
    }
}
