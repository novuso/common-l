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
interface Filter extends CommandBus
{
    /**
     * Sets the outbound pipe
     *
     * The instance passed to this method must receive a delegated call to its
     * execute method during command execution. This allows the command to pass
     * completely through the pipeline.
     *
     * Example:
     *
     *     public function execute(Command $command)
     *     {
     *         // do something with $command
     *
     *         $this->outbound->execute($command);
     *     }
     *
     * @param CommandBus $outbound A CommandBus instance
     *
     * @return void
     */
    public function setOutbound(CommandBus $outbound);
}
