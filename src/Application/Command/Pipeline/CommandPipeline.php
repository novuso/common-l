<?php

namespace Novuso\Common\Application\Command\Pipeline;

use Novuso\Common\Application\Command\Command;
use Novuso\Common\Application\Command\CommandBus;
use Novuso\Common\Application\Command\Filter;

/**
 * CommandPipeline is a command pipeline
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
    protected $pipeline;

    /**
     * Constructs CommandPipeline
     *
     * @param ApplicationBus $commandBus The application bus
     */
    public function __construct(ApplicationBus $commandBus)
    {
        $this->pipeline = $commandBus;
    }

    /**
     * Adds a filter to the pipeline
     *
     * @param Filter $filter The command filter
     *
     * @return void
     */
    public function addFilter(Filter $filter)
    {
        $filter->setOutbound($this->pipeline);
        $this->pipeline = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command)
    {
        $this->pipeline->execute($command);
    }
}
