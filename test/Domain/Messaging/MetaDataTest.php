<?php

namespace Novuso\Test\Common\Domain\Messaging;

use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\System\Serialization\JsonSerializer;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Messaging\MetaData
 */
class MetaDataTest extends PHPUnit_Framework_TestCase
{
    public function test_that_it_is_serializable()
    {
        $serializer = new JsonSerializer();
        $metaData = new MetaData([
            'credentials' => [
                'username' => 'jrnickell',
                'password' => 'secret'
            ],
            'ip_address'  => '127.0.0.1'
        ]);
        $string = $serializer->serialize($metaData);
        $object = $serializer->deserialize($string);
        $this->assertSame('jrnickell', $object->get('credentials')['username']);
    }

    public function test_that_is_empty_returns_true_when_data_is_empty()
    {
        $metaData = new MetaData();
        $this->assertTrue($metaData->isEmpty());
    }

    public function test_that_is_empty_returns_false_when_data_is_present()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $this->assertFalse($metaData->isEmpty());
    }

    public function test_that_count_returns_expected_count()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $this->assertSame(2, count($metaData));
    }

    public function test_that_get_returns_expected_value_for_key()
    {
        $metaData = new MetaData();
        $metaData->set('username', 'jrnickell');
        $this->assertSame('jrnickell', $metaData->get('username'));
    }

    public function test_that_offset_get_returns_expected_value_for_key()
    {
        $metaData = new MetaData();
        $metaData['username'] = 'jrnickell';
        $this->assertSame('jrnickell', $metaData['username']);
    }

    public function test_that_has_returns_true_for_matching_key()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $this->assertTrue($metaData->has('ip_address'));
    }

    public function test_that_offset_exists_returns_true_for_matching_key()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $this->assertTrue(isset($metaData['ip_address']));
    }

    public function test_that_has_returns_false_for_missing_key()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $this->assertFalse($metaData->has('location'));
    }

    public function test_that_offset_exists_returns_false_for_missing_key()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $this->assertFalse(isset($metaData['location']));
    }

    public function test_that_remove_correctly_removes_by_key()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $metaData->remove('username');
        $this->assertFalse($metaData->has('username'));
    }

    public function test_that_offset_unset_correctly_removes_by_key()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        unset($metaData['username']);
        $this->assertFalse(isset($metaData['username']));
    }

    public function test_that_keys_returns_expected_list_of_keys()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $this->assertSame(['username', 'ip_address'], $metaData->keys());
    }

    public function test_that_merge_correctly_merges_data()
    {
        $metaData = new MetaData(['username' => 'jrnickell']);
        $metaData->merge(new MetaData(['ip_address' => '127.0.0.1']));
        $this->assertTrue($metaData['username'] === 'jrnickell' && $metaData['ip_address'] === '127.0.0.1');
    }

    public function test_that_to_string_returns_expected_string()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $expected = '{"username":"jrnickell","ip_address":"127.0.0.1"}';
        $this->assertSame($expected, $metaData->toString());
    }

    public function test_that_string_cast_returns_expected_string()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $expected = '{"username":"jrnickell","ip_address":"127.0.0.1"}';
        $this->assertSame($expected, (string) $metaData);
    }

    public function test_that_it_is_traversable()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $count = 0;
        foreach ($metaData as $key => $value) {
            $count++;
        }
        $this->assertSame(2, $count);
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_set_throws_exception_for_invalid_value()
    {
        $metaData = new MetaData();
        $metaData->set('foo', [new \stdClass()]);
    }

    /**
     * @expectedException Novuso\System\Exception\KeyException
     */
    public function test_that_get_throws_exception_for_key_not_found()
    {
        $metaData = new MetaData([
            'username'   => 'jrnickell',
            'ip_address' => '127.0.0.1'
        ]);
        $metaData->get('location');
    }
}
