<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Model\Api\RootEntity;
use Novuso\System\Utility\Test;

/**
 * AggregateRoot is the base class for an aggregate root entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class AggregateRoot implements RootEntity
{
    use Identity;
    use EventRecords;
}
