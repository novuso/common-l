<?php

namespace Novuso\Common\Application\Messaging\Query;

use Exception;
use Novuso\Common\Application\Messaging\Query\Exception\QueryException;
use Novuso\Common\Application\Messaging\Query\Resolver\QueryHandlerResolver;
use Novuso\Common\Domain\Messaging\Query\DomainQueryMessage;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryFilter;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Novuso\Common\Domain\Messaging\Query\ViewModel;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Collection\LinkedStack;

/**
 * QueryPipeline is a query pipeline
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueryPipeline implements QueryService, QueryFilter
{
    /**
     * Query handler resolver
     *
     * @var QueryHandlerResolver
     */
    protected $resolver;

    /**
     * Query filters
     *
     * @var LinkedStack
     */
    protected $filters;

    /**
     * Constructs QueryPipeline
     *
     * @param QueryHandlerResolver $resolver The handler resolver
     * @param QueryFilter[]        $filters  A list of filters
     */
    public function __construct(QueryHandlerResolver $resolver, array $filters = [])
    {
        $this->resolver = $resolver;

        $this->filters = LinkedStack::of(QueryFilter::class);
        $this->filters->push($this);

        $this->addFilters($filters);
    }

    /**
     * Adds filters to the pipeline
     *
     * @param QueryFilter[] $filters A list of filters
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
     * @param QueryFilter $filter The filter
     *
     * @return void
     */
    public function addFilter(QueryFilter $filter)
    {
        $this->filters->push($filter);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Query $query)
    {
        try {
            $timetamp = DateTime::now();
            $messageId = MessageId::generate();
            $metaData = new MetaData();
            $viewModel = $this->pipe(new DomainQueryMessage($messageId, $timetamp, $query, $metaData));
        } catch (Exception $exception) {
            throw QueryException::create($exception->getMessage(), $exception);
        }

        return $viewModel;
    }

    /**
     * {@inheritdoc}
     */
    public function process(QueryMessage $message, callable $next)
    {
        $query = $message->payload();
        $handler = $this->resolver->resolve($query);

        return $handler->handle($query);
    }

    /**
     * Pipes query to the next filter
     *
     * @param QueryMessage $message The query message
     *
     * @return ViewModel
     */
    public function pipe(QueryMessage $message)
    {
        $filter = $this->filters->pop();

        return $filter->process($message, [$this, 'pipe']);
    }
}
