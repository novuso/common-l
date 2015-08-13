<?php

namespace Novuso\Common\Application\Messaging\Command;

use Exception;
use Novuso\Common\Application\Messaging\Command\Exception\CommandException;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandFilter;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Collection\LinkedStack;

/**
 * CommandPipeline is a command pipeline
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class CommandPipeline implements CommandBus, CommandFilter
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
     * @param CommandBus      $commandBus The command bus
     * @param CommandFilter[] $filters    A list of filters
     */
    public function __construct(CommandBus $commandBus, array $filters = [])
    {
        $this->commandBus = $commandBus;

        $this->filters = LinkedStack::of(CommandFilter::class);
        $this->filters->push($this);

        $this->addFilters($filters);
    }

    /**
     * Adds filters to the pipeline
     *
     * @param CommandFilter[] $filters A list of filters
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
     * @param CommandFilter $filter The filter
     *
     * @return void
     */
    public function addFilter(CommandFilter $filter)
    {
        $this->filters->push($filter);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command)
    {
        $timetamp = DateTime::now();
        $messageId = MessageId::generate();
        $metaData = new MetaData();

        $this->pipe(new CommandMessage($messageId, $timetamp, $command, $metaData));
    }

    /**
     * {@inheritdoc}
     */
    public function process(CommandMessage $message, callable $next)
    {
        $this->commandBus->execute($message->payload());
    }

    /**
     * Pipes command to the next filter
     *
     * @param CommandMessage $message The command message
     *
     * @return void
     */
    public function pipe(CommandMessage $message)
    {
        try {
            $filter = $this->filters->pop();
            $filter->process($message, [$this, 'pipe']);
        } catch (Exception $exception) {
            throw CommandException::create($exception->getMessage(), $exception);
        }
    }
}
