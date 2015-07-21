<?php

namespace Novuso\Common\Application\Command\Pipeline;

use Exception;

use Novuso\Common\Application\Command\Command;
use Novuso\Common\Application\Command\CommandBus;
use Novuso\Common\Application\Command\Exception\CommandException;
use Novuso\Common\Application\Command\Filter;
use Novuso\System\Collection\LinkedStack;

/**
 * CommandPipeline is a command pipeline
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class CommandPipeline implements CommandBus, Filter
{
    /**
     * Command bus
     *
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * Command filters
     *
     * @var LinkedStack
     */
    protected $filters;

    /**
     * Constructs CommandPipeline
     *
     * @param CommandBus $commandBus The command bus
     * @param Filter[]   $filters    A list of filters
     */
    public function __construct(CommandBus $commandBus, array $filters = [])
    {
        $this->commandBus = $commandBus;

        $this->filters = LinkedStack::of(Filter::class);
        $this->filters->push($this);

        $this->addFilters($filters);
    }

    /**
     * Adds filters to the pipeline
     *
     * @param Filter[] $filters A list of filters
     *
     * @return void
     */
    public function addFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * Adds a filter to the pipeline
     *
     * @param Filter $filter The filter
     *
     * @return void
     */
    public function addFilter(Filter $filter)
    {
        $this->filters->push($filter);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command)
    {
        $this->pipe($command);
    }

    /**
     * {@inheritdoc}
     */
    public function process(Command $command, callable $next)
    {
        $this->commandBus->execute($command);
    }

    /**
     * Pipes command to the next filter
     *
     * @param Command $command The command
     *
     * @return void
     */
    public function pipe(Command $command)
    {
        try {
            $filter = $this->filters->pop();
            $filter->process($command, [$this, 'pipe']);
        } catch (Exception $exception) {
            throw CommandException::create($exception->getMessage(), $exception);
        }
    }
}
