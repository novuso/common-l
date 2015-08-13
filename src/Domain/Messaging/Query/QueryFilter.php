<?php

namespace Novuso\Common\Domain\Messaging\Query;

use Exception;

/**
 * QueryFilter is the interface for a query filter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface QueryFilter
{
    /**
     * Processes a query message and the return value of the next filter
     *
     * Next signature: function (QueryMessage $message): mixed {}
     *
     * @param QueryMessage $message The query message
     * @param callable     $next    The next filter
     *
     * @return mixed
     *
     * @throws Exception When an error occurs during processing
     */
    public function process(QueryMessage $message, callable $next);
}
