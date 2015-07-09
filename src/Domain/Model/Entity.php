<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model;

/**
 * Entity is the interface for a domain entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Entity
{
    /**
     * Retrieves a unique identifier
     *
     * @return Identifier
     */
    public function id();
}
