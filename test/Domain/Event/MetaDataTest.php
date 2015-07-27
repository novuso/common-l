<?php

namespace Novuso\Test\Common\Domain\Event;

use Novuso\Common\Domain\Event\MetaData;
use Novuso\System\Serialization\JsonSerializer;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Event\MetaData
 */
class MetaDataTest extends PHPUnit_Framework_TestCase
{
    public function test_that_it_is_serializable()
    {
        $serializer = new JsonSerializer();
        $metaData = new MetaData([
            'username'  => 'jrnickell',
            'ipAddress' => '127.0.0.1'
        ]);
        $string = $serializer->serialize($metaData);
        $object = $serializer->deserialize($string);
        $this->assertSame('jrnickell', $object->get('username'));
    }

    public function test_that_is_empty_returns_true_when_data_is_empty()
    {
        $metaData = new MetaData();
        $this->assertTrue($metaData->isEmpty());
    }

    public function test_that_is_empty_returns_false_when_data_is_present()
    {
        $metaData = new MetaData([
            'username'  => 'jrnickell',
            'ipAddress' => '127.0.0.1'
        ]);
        $this->assertFalse($metaData->isEmpty());
    }
}
