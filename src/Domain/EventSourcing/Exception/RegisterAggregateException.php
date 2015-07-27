<?php

namespace Novuso\Common\Domain\EventSourcing\Exception;

use Exception;

/**
 * RegisterAggregateException is thrown for invalid aggregate registration
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class RegisterAggregateException extends EventSourcingException
{
    /**
     * Constructs RegisterAggregateException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 601, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return RegisterAggregateException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 601, $previous);
    }
}
