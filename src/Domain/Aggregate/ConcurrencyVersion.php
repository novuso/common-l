<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Aggregate;

/**
 * ConcurrencyVersion provides methods for concurrency versioning
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
trait ConcurrencyVersion
{
    /**
     * Concurrency version
     *
     * @var int
     */
    protected $concurrencyVersion = 1;

    /**
     * Retrieves the concurrency version
     *
     * @return int
     */
    public function concurrencyVersion(): int
    {
        return $this->concurrencyVersion;
    }

    /**
     * Increments the concurrency version
     *
     * @return void
     */
    public function incrementConcurrencyVersion()
    {
        $this->concurrencyVersion++;
    }
}
