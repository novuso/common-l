<?php

namespace Novuso\Common\Domain\Messaging\Command;

use Exception;

/**
 * CommandHandler is the interface for a command handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
interface CommandHandler
{
    /**
     * Handles a command
     *
     * @param Command $command The command
     *
     * @return void
     *
     * @throws Exception When an error occurs during processing
     */
    public function handle(Command $command);
}
