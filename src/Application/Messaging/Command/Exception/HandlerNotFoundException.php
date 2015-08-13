<?php

namespace Novuso\Common\Application\Messaging\Command\Exception;

use Exception;

/**
 * HandlerNotFoundException is thrown when a command handler is not found
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class HandlerNotFoundException extends CommandException
{
    /**
     * Constructs HandlerNotFoundException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 1102, Exception $previous = null)
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
        return new static($message, 1102, $previous);
    }
}
