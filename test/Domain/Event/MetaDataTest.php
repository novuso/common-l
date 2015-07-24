<?php

namespace Novuso\Test\Common\Domain\Event;

use Novuso\Common\Domain\Event\MetaData;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Event\MetaData
 */
class MetaDataTest extends PHPUnit_Framework_TestCase
{
    public function test_that_it_is_empty_returns_true_for_empty_data()
    {
        $metaData = new MetaData([]);
        $this->assertTrue($metaData->isEmpty());
    }
}
