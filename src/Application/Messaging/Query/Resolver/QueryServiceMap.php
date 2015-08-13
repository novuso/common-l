<?php

namespace Novuso\Common\Application\Messaging\Query\Resolver;

use Novuso\Common\Application\Container\Container;
use Novuso\Common\Application\Container\Exception\ServiceContainerException;
use Novuso\Common\Application\Messaging\Query\Exception\HandlerNotFoundException;
use Novuso\Common\Application\Messaging\Query\Exception\InvalidQueryException;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * QueryServiceMap is a query class to handler service map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class QueryServiceMap
{
    /**
     * Service container
     *
     * @var Container
     */
    protected $container;

    /**
     * Handler map
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Constructs QueryServiceMap
     *
     * @param Container $container The service container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Registers query handlers
     *
     * The query to handler map must follow this format:
     * [
     *     SomeQuery::class => 'handler_service_id'
     * ]
     *
     * @param array $queryToHandlerMap A map of class names to service IDs
     *
     * @return void
     *
     * @throws InvalidQueryException When a query class is not valid
     */
    public function registerHandlers(array $queryToHandlerMap)
    {
        foreach ($queryToHandlerMap as $queryClass => $serviceId) {
            $this->registerHandler($queryClass, $serviceId);
        }
    }

    /**
     * Registers a query handler
     *
     * @param string $queryClass The full query class name
     * @param string $serviceId  The handler service ID
     *
     * @return void
     *
     * @throws InvalidQueryException When the query class is not valid
     */
    public function registerHandler($queryClass, $serviceId)
    {
        if (!Test::implementsInterface($queryClass, Query::class)) {
            $message = sprintf('Invalid query class: %s', $queryClass);
            throw InvalidQueryException::create($message);
        }

        $type = Type::create($queryClass)->toString();

        $this->handlers[$type] = (string) $serviceId;
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

        $serviceId = $this->handlers[$type];

        try {
            $handler = $this->container->get($serviceId);
        } catch (ServiceContainerException $exception) {
            throw HandlerNotFoundException::create($exception->getMessage(), $exception);
        }

        return $handler;
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

        if (!isset($this->handlers[$type])) {
            return false;
        }

        $serviceId = $this->handlers[$type];

        return $this->container->has($serviceId);
    }
}
