<?php

namespace Novuso\Common\Application\Container\Exception;

use Exception;

/**
 * ServiceContainerException is the exception thrown by the service container
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceContainerException extends Exception
{
    /**
     * Constructs ServiceContainerException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 1500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates an exception instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return ServiceContainerException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 1500, $previous);
    }
}
