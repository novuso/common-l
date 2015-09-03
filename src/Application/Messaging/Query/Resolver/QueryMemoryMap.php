<?php

namespace Novuso\Common\Application\Messaging\Query\Resolver;

use Novuso\Common\Application\Messaging\Query\Exception\HandlerNotFoundException;
use Novuso\Common\Application\Messaging\Query\Exception\InvalidQueryException;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * QueryMemoryMap is a query class to handler instance map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueryMemoryMap
{
    /**
     * Handler map
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Registers query handlers
     *
     * The query to handler map must follow this format:
     * [
     *     SomeQuery::class => $someHandlerInstance
     * ]
     *
     * @param array $queryToHandlerMap A map of class names to handlers
     *
     * @return void
     *
     * @throws InvalidQueryException When a query class is not valid
     */
    public function registerHandlers(array $queryToHandlerMap)
    {
        foreach ($queryToHandlerMap as $queryClass => $handler) {
            $this->registerHandler($queryClass, $handler);
        }
    }

    /**
     * Registers a query handler
     *
     * @param string       $queryClass The full query class name
     * @param QueryHandler $handler    The query handler
     *
     * @return void
     *
     * @throws InvalidQueryException When the query class is not valid
     */
    public function registerHandler($queryClass, QueryHandler $handler)
    {
        if (!Test::implementsInterface($queryClass, Query::class)) {
            $message = sprintf('Invalid query class: %s', $queryClass);
            throw InvalidQueryException::create($message);
        }

        $type = Type::create($queryClass)->toString();

        $this->handlers[$type] = $handler;
    }

    /**
     * Retrieves a handler by query class name
     *
     * @param string $queryClass The full query class name
     *
     * @return QueryHandler
     *
     * @throws HandlerNotFoundException When the handler is not found
     */
    public function getHandler($queryClass)
    {
        $type = Type::create($queryClass)->toString();

        if (!isset($this->handlers[$type])) {
            $message = sprintf('Handler not defined for query: %s', $queryClass);
            throw HandlerNotFoundException::create($message);
        }

        return $this->handlers[$type];
    }

    /**
     * Checks if a handler is defined for a query
     *
     * @param string $queryClass The full query class name
     *
     * @return bool
     */
    public function hasHandler($queryClass)
    {
        $type = Type::create($queryClass)->toString();

        return isset($this->handlers[$type]);
    }
}
