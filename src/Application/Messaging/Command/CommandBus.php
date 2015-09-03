<?php

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Application\Messaging\Command\Exception\CommandException;
use Novuso\Common\Domain\Messaging\Command\Command;

/**
 * CommandBus is the interface for a command bus
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface CommandBus
{
    /**
     * Executes a command
     *
     * @param Command $command The command
     *
     * @return void
     *
     * @throws CommandException When an error occurs during processing
     */
    public function execute(Command $command);
}
