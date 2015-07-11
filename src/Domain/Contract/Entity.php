<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Contract;

use Novuso\System\Type\{Comparable, Equatable};

/**
 * Entity is the interface for a domain entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Entity extends Comparable, Equatable
{
    /**
     * Retrieves a unique identifier
     *
     * @return Identifier
     */
    public function id(): Identifier;
}
