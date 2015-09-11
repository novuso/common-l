<?php

namespace Novuso\Common\Application\Messaging\Query\Filter;

use Exception;
use Novuso\Common\Application\Logging\Logger;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryFilter;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Novuso\System\Utility\ClassName;

/**
 * QueryLogger is a filter that logs query messages
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class QueryLogger implements QueryFilter
{
    /**
     * Logger
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Constructs QueryLogger
     *
     * @param Logger $logger The logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function process(QueryMessage $message, callable $next)
    {
        $query = ClassName::short($message->payloadType()->toString());

        try {
            $this->logger->debug(
                sprintf('Query (%s) received: %s', $query, date(DATE_ATOM)),
                ['message' => $message->serialize()]
            );

            $data = $next($message);

            $this->logger->debug(
                sprintf('Query (%s) handled: %s', $query, date(DATE_ATOM)),
                ['message' => $message->serialize()]
            );
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Query (%s) failed: %s', $query, date(DATE_ATOM)),
                ['message' => $message->serialize(), 'exception' => $exception]
            );
            throw $exception;
        }

        return $data;
    }
}
