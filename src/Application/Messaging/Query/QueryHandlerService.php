<?php

namespace Novuso\Common\Application\Messaging\Query;

use Exception;
use Novuso\Common\Application\Messaging\Query\Exception\QueryException;
use Novuso\Common\Application\Messaging\Query\Resolver\QueryHandlerResolver;
use Novuso\Common\Domain\Messaging\Query\Query;

/**
 * QueryHandlerService retrieves a view model using a handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class QueryHandlerService implements QueryService
{
    /**
     * Query handler resolver
     *
     * @var QueryHandlerResolver
     */
    protected $resolver;

    /**
     * Constructs QueryHandlerService
     *
     * @param QueryHandlerResolver $resolver The handler resolver
     */
    public function __construct(QueryHandlerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Query $query)
    {
        $handler = $this->resolver->resolve($query);

        try {
            $data = $handler->handle($query);
        } catch (Exception $exception) {
            throw QueryException::create($exception->getMessage(), $exception);
        }

        return $data;
    }
}
