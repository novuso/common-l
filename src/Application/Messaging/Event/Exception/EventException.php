<?php

namespace Novuso\Common\Application\Messaging\Event\Exception;

use Exception;

/**
 * EventException is the exception type thrown by the event component
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EventException extends Exception
{
    /**
     * Constructs EventException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 1110, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return EventException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 1110, $previous);
    }
}
