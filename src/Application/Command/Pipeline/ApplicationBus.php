<?php declare(strict_types=1);

namespace Novuso\Common\Application\Command\Pipeline;

use Exception;
use Novuso\Common\Application\Command\{Command, CommandBus};
use Novuso\Common\Application\Command\Exception\CommandException;
use Novuso\Common\Application\Command\Resolver\HandlerResolver;

/**
 * ApplicationBus is the core application command bus
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ApplicationBus implements CommandBus
{
    /**
     * Handler resolver
     *
     * @var HandlerResolver
     */
    protected $resolver;

    /**
     * Constructs ApplicationBus
     *
     * @param HandlerResolver $resolver The handler resolver
     */
    public function __construct(HandlerResolver $resolver)
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
            $handler->execute($command);
        } catch (Exception $exception) {
            throw CommandException::create($exception->getMessage(), $exception);
        }
    }
}
