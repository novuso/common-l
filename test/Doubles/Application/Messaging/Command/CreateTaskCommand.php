<?php

namespace Novuso\Test\Common\Doubles\Application\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Model\Serialization;

class CreateTaskCommand implements Command
{
    use Serialization;

    private $description;

    public function __construct($description)
    {
        $this->description = $description;
    }

    public function description()
    {
        return $this->description;
    }

    public function jsonSerialize()
    {
        return ['description' => $this->description];
    }
}
