<?php

namespace Novuso\Common\Domain\EventStore\Exception;

use Exception;

/**
 * StreamNotFoundException is thrown when an event stream is not found
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class StreamNotFoundException extends EventStoreException
{
    /**
     * Constructs StreamNotFoundException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 501, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return StreamNotFoundException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 501, $previous);
    }
}
