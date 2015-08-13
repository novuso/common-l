<?php

namespace Novuso\Common\Domain\Messaging\Command;

use Exception;

/**
 * CommandFilter is the interface for a command filter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
interface CommandFilter
{
    /**
     * Processes a command message and calls the next filter
     *
     * Next signature: function (CommandMessage $message): void {}
     *
     * @param CommandMessage $message The command message
     * @param callable       $next    The next filter
     *
     * @return void
     *
     * @throws Exception When an error occurs during processing
     */
    public function process(CommandMessage $message, callable $next);
}
