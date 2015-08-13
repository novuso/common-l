<?php

namespace Novuso\Common\Application\Messaging\Query\Resolver;

use Novuso\Common\Application\Messaging\Query\Exception\HandlerNotFoundException;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;

/**
 * QueryHandlerResolver resolves an application query to a handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface QueryHandlerResolver
{
    /**
     * Retrieves a handler for a query
     *
     * @param Query $query The query
     *
     * @return QueryHandler
     *
     * @throws HandlerNotFoundException When the handler is not found
     */
    public function resolve(Query $query);
}
