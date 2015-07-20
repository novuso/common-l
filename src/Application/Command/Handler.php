<?php

namespace Novuso\Common\Application\Command;

use Exception;

/**
 * Handler is the interface for a command handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Handler
{
    /**
     * Executes a command
     *
     * @param Command $command The command
     *
     * @return void
     *
     * @throws Exception When an error occurs during processing
     */
    public function execute(Command $command);
}
