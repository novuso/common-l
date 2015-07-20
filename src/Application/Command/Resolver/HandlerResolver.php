<?php

namespace Novuso\Common\Application\Command\Resolver;

use Novuso\Common\Application\Command\Command;
use Novuso\Common\Application\Command\Handler;
use Novuso\Common\Application\Command\Exception\HandlerNotFoundException;

/**
 * HandlerResolver resolves an application command to a handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface HandlerResolver
{
    /**
     * Retrieves a handler for a command
     *
     * @param Command $command The command
     *
     * @return Handler
     *
     * @throws HandlerNotFoundException When the handler is not found
     */
    public function resolve(Command $command);
}
