<?php

namespace Novuso\Common\Domain\EventStore\Exception;

use Exception;

/**
 * ConcurrencyException is thrown when a concurrency violation occurs
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ConcurrencyException extends EventStoreException
{
    /**
     * Constructs ConcurrencyException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 502, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return ConcurrencyException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 502, $previous);
    }
}
