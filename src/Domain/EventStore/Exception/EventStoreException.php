<?php

namespace Novuso\Common\Domain\EventStore\Exception;

use Exception;

/**
 * EventStoreException is the exception type thrown by the event store
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EventStoreException extends Exception
{
    /**
     * Constructs EventStoreException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return EventStoreException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 500, $previous);
    }
}
