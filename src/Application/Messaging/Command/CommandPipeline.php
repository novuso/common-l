<?php

namespace Novuso\Common\Application\Messaging\Command;

use Exception;
use Novuso\Common\Application\Messaging\Command\Exception\CommandException;
use Novuso\Common\Application\Messaging\Command\Resolver\CommandHandlerResolver;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandFilter;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\Command\DomainCommandMessage;
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
 */
class CommandPipeline implements CommandBus, CommandFilter
{
    /**
     * Command handler resolver
     *
     * @var CommandHandlerResolver
     */
    protected $resolver;

    /**
     * Command filters
     *
     * @var LinkedStack
     */
    protected $filters;

    /**
     * Constructs CommandPipeline
     *
     * @param CommandHandlerResolver $resolver The handler resolver
     * @param CommandFilter[]        $filters  A list of filters
     */
    public function __construct(CommandHandlerResolver $resolver, array $filters = [])
    {
        $this->resolver = $resolver;

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
        try {
            $timetamp = DateTime::now();
            $messageId = MessageId::generate();
            $metaData = new MetaData();
            $this->pipe(new DomainCommandMessage($messageId, $timetamp, $command, $metaData));
        } catch (Exception $exception) {
            throw CommandException::create($exception->getMessage(), $exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process(CommandMessage $message, callable $next)
    {
        $command = $message->payload();
        $handler = $this->resolver->resolve($command);
        $handler->handle($command);
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
        $filter = $this->filters->pop();
        $filter->process($message, [$this, 'pipe']);
    }
}
