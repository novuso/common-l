<?php declare(strict_types=1);

namespace Novuso\Common\Application\Command\Pipeline;

use Novuso\Common\Application\Command\{Command, CommandBus, Middleware};

/**
 * CommandPipeline is a pipeline of command bus middleware
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class CommandPipeline implements CommandBus
{
    /**
     * Command bus
     *
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * Constructs CommandPipeline
     *
     * @param ApplicationBus $commandBus The application bus
     */
    public function __construct(ApplicationBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Adds middleware to the pipeline
     *
     * @param Middleware $middleware The command bus middleware
     *
     * @return void
     */
    public function addMiddleware(Middleware $middleware)
    {
        $middleware->setCommandBus($this->commandBus);
        $this->commandBus = $middleware;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command)
    {
        $this->commandBus->execute($command);
    }
}
