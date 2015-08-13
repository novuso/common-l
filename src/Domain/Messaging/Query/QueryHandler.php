<?php

namespace Novuso\Common\Domain\Messaging\Query;

use Exception;

/**
 * QueryHandler is the interface for a query handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
interface QueryHandler
{
    /**
     * Handles a query to retrieve data
     *
     * @param Query $query The query
     *
     * @return mixed
     *
     * @throws Exception When an error occurs during processing
     */
    public function handle(Query $query);
}
