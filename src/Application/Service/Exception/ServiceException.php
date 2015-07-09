<?php declare(strict_types=1);

namespace Novuso\Common\Application\Service\Exception;

use Exception;

/**
 * ServiceException is the exception type thrown by a service container
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ServiceException extends Exception
{
    /**
     * Constructs ServiceException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct(string $message = '', int $code = 1500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates an exception instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return ServiceException
     */
    public static function create(string $message = '', Exception $previous = null): Exception
    {
        return new static($message, 1500, $previous);
    }
}
