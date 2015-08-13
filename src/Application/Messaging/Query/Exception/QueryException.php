<?php

namespace Novuso\Common\Application\Messaging\Query\Exception;

use Exception;

/**
 * QueryException is the exception type thrown by the query component
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class QueryException extends Exception
{
    /**
     * Constructs QueryException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 1120, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return QueryException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 1120, $previous);
    }
}
