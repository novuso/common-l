<?php

namespace Novuso\Common\Application\Command;

/**
 * Filter is the interface for a command filter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Filter
{
    /**
     * Processes a command and calls the next pipe
     *
     * Next signature: function (Command $command): void {}
     *
     * @param Command  $command The command
     * @param callable $next    The next pipe
     *
     * @return void
     */
    public function process(Command $command, callable $next);
}
