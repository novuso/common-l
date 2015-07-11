<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Contract;

use Novuso\Common\Domain\Event\EventMessages;

/**
 * AggregateRoot is the interface for an aggregate root
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface AggregateRoot extends Entity
{
    /**
     * Retrieves and clears recorded event messages
     *
     * @return EventMessages
     */
    public function extractRecordedEvents(): EventMessages;

    /**
     * Retrieves the concurrency version
     *
     * @return int
     */
    public function concurrencyVersion(): int;
}
