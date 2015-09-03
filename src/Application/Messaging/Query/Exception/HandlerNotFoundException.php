<?php

namespace Novuso\Common\Application\Messaging\Query\Exception;

use Exception;

/**
 * HandlerNotFoundException is thrown when a query handler is not found
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class HandlerNotFoundException extends QueryException
{
    /**
     * Constructs HandlerNotFoundException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 1122, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return HandlerNotFoundException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 1122, $previous);
    }
}
