<?php

namespace Novuso\Test\Common\Doubles\Application\Messaging\Command;

use Exception;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;

class ErrorTaskHandler implements CommandHandler
{
    public function handle(Command $command)
    {
        throw new Exception('Something went wrong');
    }
}
