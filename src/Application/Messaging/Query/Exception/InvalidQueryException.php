<?php

namespace Novuso\Common\Application\Messaging\Query\Exception;

use Exception;

/**
 * InvalidQueryException is thrown when referencing an invalid query
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class InvalidQueryException extends QueryException
{
    /**
     * Constructs InvalidQueryException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 1121, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return InvalidQueryException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 1121, $previous);
    }
}
