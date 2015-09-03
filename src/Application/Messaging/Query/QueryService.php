<?php

namespace Novuso\Common\Application\Messaging\Query;

use Novuso\Common\Application\Messaging\Query\Exception\QueryException;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\ViewData;

/**
 * QueryService is the interface for a query service
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryService
{
    /**
     * Retrieves data for a given query
     *
     * @param Query $query The query
     *
     * @return ViewData
     *
     * @throws QueryException When an error occurs during processing
     */
    public function fetch(Query $query);
}
