<?php

namespace Novuso\Common\Application\Service\Exception;

use Exception;

/**
 * EntryNotFoundException is thrown when a container entry is not found
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class EntryNotFoundException extends ServiceException
{
    /**
     * Constructs EntryNotFoundException
     *
     * @param string         $message  The exception message
     * @param int            $code     The exception code
     * @param Exception|null $previous The previous exception for chaining
     */
    public function __construct($message = '', $code = 1501, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates an exception instance
     *
     * @param string         $message  The exception message
     * @param Exception|null $previous The previous exception for chaining
     *
     * @return EntryNotFoundException
     */
    public static function create($message = '', Exception $previous = null)
    {
        return new static($message, 1501, $previous);
    }
}
