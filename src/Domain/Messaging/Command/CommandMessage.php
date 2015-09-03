<?php

namespace Novuso\Common\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Message;

/**
 * CommandMessage is the interface for a domain command message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface CommandMessage extends Message
{
    /**
     * Retrieves the payload
     *
     * @return Command
     */
    public function payload();
}
