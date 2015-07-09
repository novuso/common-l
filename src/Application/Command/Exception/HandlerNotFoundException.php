<?php declare(strict_types=1);

namespace Novuso\Common\Application\Command\Exception;

use Exception;

/**
 * HandlerNotFoundException is thrown when a command handler is not found
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
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
    public function __construct(string $message = '', int $code = 1102, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return Exception
     */
    public static function create(string $message = '', Exception $previous = null): Exception
    {
        return new static($message, 1102, $previous);
    }
}
