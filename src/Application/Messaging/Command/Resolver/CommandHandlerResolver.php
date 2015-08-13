<?php

namespace Novuso\Common\Application\Messaging\Command\Resolver;

use Novuso\Common\Application\Messaging\Command\Exception\HandlerNotFoundException;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;

/**
 * CommandHandlerResolver resolves an application command to a handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface CommandHandlerResolver
{
    /**
     * Retrieves a handler for a command
     *
     * @param Command $command The command
     *
     * @return CommandHandler
     *
     * @throws HandlerNotFoundException When the handler is not found
     */
    public function resolve(Command $command);
}
