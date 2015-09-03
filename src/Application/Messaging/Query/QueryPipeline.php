<?php

namespace Novuso\Common\Application\Messaging\Query;

use Exception;
use Novuso\Common\Application\Messaging\Query\Exception\QueryException;
use Novuso\Common\Domain\Messaging\Query\DomainQueryMessage;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryFilter;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Novuso\Common\Domain\Messaging\Query\ViewData;
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
     * Query service
     *
     * @var QueryService
     */
    protected $queryService;

    /**
     * Query filters
     *
     * @var LinkedStack
     */
    protected $filters;

    /**
     * Constructs QueryPipeline
     *
     * @param QueryService  $queryService The query service
     * @param QueryFilter[] $filters      A list of filters
     */
    public function __construct(QueryService $queryService, array $filters = [])
    {
        $this->queryService = $queryService;

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
        $timetamp = DateTime::now();
        $messageId = MessageId::generate();
        $metaData = new MetaData();

        return $this->pipe(new DomainQueryMessage($messageId, $timetamp, $query, $metaData));
    }

    /**
     * {@inheritdoc}
     */
    public function process(QueryMessage $message, callable $next)
    {
        return $this->queryService->fetch($message->payload());
    }

    /**
     * Pipes query to the next filter
     *
     * @param QueryMessage $message The query message
     *
     * @return ViewData
     */
    public function pipe(QueryMessage $message)
    {
        try {
            $filter = $this->filters->pop();
            $viewData = $filter->process($message, [$this, 'pipe']);
        } catch (Exception $exception) {
            throw QueryException::create($exception->getMessage(), $exception);
        }

        return $viewData;
    }
}
