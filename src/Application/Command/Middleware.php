<?php declare(strict_types=1);

namespace Novuso\Common\Application\Command;

/**
 * Middleware is the interface for command bus middleware
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Middleware extends CommandBus
{
    /**
     * Sets the command bus
     *
     * The command bus passed to this method must receive a delegated call to
     * the execute method. This allows the command to pass through the pipeline
     * to be handled.
     *
     * @param CommandBus $commandBus A CommandBus instance
     *
     * @return void
     */
    public function setCommandBus(CommandBus $commandBus);
}
