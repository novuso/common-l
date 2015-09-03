<?php

namespace Novuso\Common\Application\Messaging\Command;

use Exception;
use Novuso\Common\Application\Messaging\Command\Exception\CommandException;
use Novuso\Common\Application\Messaging\Command\Resolver\CommandHandlerResolver;
use Novuso\Common\Domain\Messaging\Command\Command;

/**
 * CommandHandlerBus executes a command using a handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CommandHandlerBus implements CommandBus
{
    /**
     * Command handler resolver
     *
     * @var CommandHandlerResolver
     */
    protected $resolver;

    /**
     * Constructs CommandHandlerBus
     *
     * @param CommandHandlerResolver $resolver The handler resolver
     */
    public function __construct(CommandHandlerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command)
    {
        $handler = $this->resolver->resolve($command);

        try {
            $handler->handle($command);
        } catch (Exception $exception) {
            throw CommandException::create($exception->getMessage(), $exception);
        }
    }
}
