<?php

namespace Novuso\Common\Application\Command;

use Novuso\Common\Application\Command\Exception\CommandException;

/**
 * CommandBus is the interface for a command bus
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
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
