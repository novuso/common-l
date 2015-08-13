<?php

namespace Novuso\Common\Application\Messaging\Query\Resolver;

use Novuso\Common\Domain\Messaging\Query\Query;

/**
 * QueryServiceResolver resolves handlers from an QueryServiceMap
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class QueryServiceResolver implements QueryHandlerResolver
{
    /**
     * Handler map
     *
     * @var QueryServiceMap
     */
    protected $handlerMap;

    /**
     * Constructs QueryServiceResolver
     *
     * @param QueryServiceMap $handlerMap The handler map
     */
    public function __construct(QueryServiceMap $handlerMap)
    {
        $this->handlerMap = $handlerMap;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Query $query)
    {
        return $this->handlerMap->getHandler(get_class($query));
    }
}
