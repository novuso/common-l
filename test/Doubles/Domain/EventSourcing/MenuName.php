<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Model\Serialization;
use Novuso\Common\Domain\Model\StringCast;
use Novuso\Common\Domain\Model\StringEquals;
use Novuso\Common\Domain\Model\StringJson;
use Novuso\Common\Domain\Model\ValueObject;

final class MenuName implements ValueObject
{
    use Serialization;
    use StringCast;
    use StringEquals;
    use StringJson;

    private $name;

    private function __construct($name)
    {
        $this->name = $name;
    }

    public static function fromString($name)
    {
        assert(is_string($name), sprintf('%s expects $name to be a string', __METHOD__));

        return new self($name);
    }

    public function toString()
    {
        return $this->name;
    }
}
