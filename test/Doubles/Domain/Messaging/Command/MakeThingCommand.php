<?php

namespace Novuso\Test\Common\Doubles\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\Command;

final class MakeThingCommand implements Command
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
