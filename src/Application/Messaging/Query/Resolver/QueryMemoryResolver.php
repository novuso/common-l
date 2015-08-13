<?php

namespace Novuso\Common\Application\Messaging\Query\Resolver;

use Novuso\Common\Domain\Messaging\Query\Query;

/**
 * QueryMemoryResolver resolves handlers from an QueryMemoryMap
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class QueryMemoryResolver implements QueryHandlerResolver
{
    /**
     * Handler map
     *
     * @var QueryMemoryMap
     */
    protected $handlerMap;

    /**
     * Constructs QueryMemoryResolver
     *
     * @param QueryMemoryMap $handlerMap The handler map
     */
    public function __construct(QueryMemoryMap $handlerMap)
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
