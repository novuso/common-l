<?php

namespace Novuso\Common\Application\Messaging\Command\Exception;

use Exception;

/**
 * CommandException is the exception type thrown by the command component
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class CommandException extends Exception
{
    /**
     * Constructs CommandException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 1100, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return CommandException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 1100, $previous);
    }
}
