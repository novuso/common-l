<?php

namespace Novuso\Test\Common\Domain\Model;

use Novuso\Common\Domain\Model\EntityCollection;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\EntityCollection
 */
class EntityCollectionTest extends PHPUnit_Framework_TestCase
{
    public function test_that_it_is_empty_by_default()
    {
        $collection = new EntityCollection();
        $this->assertTrue($collection->isEmpty());
    }

    public function test_that_is_empty_returns_false_with_items()
    {
        $collection = new EntityCollection(['foo' => 'bar']);
        $this->assertFalse($collection->isEmpty());
    }

    public function test_that_count_returns_expected_number()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertSame(3, count($collection));
    }

    public function test_that_set_replaces_item_at_index()
    {
        $collection = new EntityCollection();
        $collection[0] = 'foo';
        $collection[0] = 'bar';
        $this->assertSame('bar', $collection[0]);
    }

    public function test_that_get_returns_null_for_item_not_found()
    {
        $collection = new EntityCollection();
        $this->assertNull($collection[0]);
    }

    public function test_that_contains_returns_true_when_item_found()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $this->assertTrue($collection->contains('foo'));
    }

    public function test_that_contains_returns_false_when_item_not_found()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $this->assertFalse($collection->contains('bar'));
    }

    public function test_that_contains_key_returns_true_when_key_found()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $this->assertTrue(isset($collection[0]));
    }

    public function test_that_contains_key_returns_false_when_key_not_found()
    {
        $collection = new EntityCollection();
        $this->assertFalse(isset($collection[0]));
    }

    public function test_that_remove_correctly_removes_element_at_key()
    {
        $collection = new EntityCollection();
        unset($collection[0]);
        $collection[] = 'foo';
        unset($collection[0]);
        $this->assertFalse(isset($collection[0]));
    }

    public function test_that_remove_element_returns_true_when_item_removed()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $this->assertTrue($collection->removeElement('foo'));
    }

    public function test_that_remove_element_returns_false_when_item_not_found()
    {
        $collection = new EntityCollection();
        $this->assertFalse($collection->removeElement('foo'));
    }

    public function test_that_clear_removes_elements_from_collection()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection->clear();
        $this->assertTrue($collection->isEmpty());
    }

    public function test_that_get_keys_returns_list_of_keys()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertSame([0, 1, 2], $collection->getKeys());
    }

    public function test_that_get_values_returns_list_of_values()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertSame(['foo', 'bar', 'baz'], $collection->getValues());
    }

    public function test_that_index_of_returns_expected_value()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertSame(1, $collection->indexOf('bar'));
    }

    public function test_that_slice_returns_expected_value()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertSame([1 => 'bar', 2 => 'baz'], $collection->slice(1));
    }

    public function test_that_it_can_be_iterated_by_direct_methods()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $array = [];
        for ($collection->first(); $collection->key() !== null; $collection->next()) {
            $array[$collection->key()] = $collection->current();
        }
        $this->assertSame(['foo', 'bar', 'baz'], $array);
    }

    public function test_that_last_returns_expected_value()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertSame('baz', $collection->last());
    }

    public function test_that_map_creates_expected_collection()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $result = $collection->map(function ($item) {
            $chars = str_split($item);

            return implode('', array_reverse($chars));
        });
        $this->assertSame(['oof', 'rab', 'zab'], $result->toArray());
    }

    public function test_that_filter_creates_expected_collection()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $result = $collection->filter(function ($item) {
            return $item[0] === 'b';
        });
        $this->assertSame([1 => 'bar', 2 => 'baz'], $result->toArray());
    }

    public function test_that_exists_returns_true_when_an_item_passes_predicate()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertTrue($collection->exists(function ($key, $item) {
            return $item[0] === 'f';
        }));
    }

    public function test_that_exists_returns_false_when_no_items_pass_predicate()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertFalse($collection->exists(function ($key, $item) {
            return $item[0] === 'h';
        }));
    }

    public function test_that_for_all_returns_true_when_all_items_pass_predicate()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertTrue($collection->forAll(function ($key, $item) {
            return strlen($item) === 3;
        }));
    }

    public function test_that_for_all_returns_false_when_an_item_does_not_pass()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $this->assertFalse($collection->forAll(function ($key, $item) {
            return $item[0] === 'b';
        }));
    }

    public function test_that_partition_returns_expected_collections()
    {
        $collection = new EntityCollection();
        $collection[] = 5;
        $collection[] = 8;
        $collection[] = 12;
        $collection[] = 13;
        list($evens, $odds) = $collection->partition(function ($key, $item) {
            return $item % 2 === 0;
        });
        $this->assertTrue([8, 12] === $evens->getValues() && [5, 13] === $odds->getValues());
    }

    public function test_that_it_is_iterable()
    {
        $collection = new EntityCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';
        $collection[] = 'baz';
        $count = 0;
        foreach ($collection as $key => $element) {
            $count++;
        }
        $this->assertSame(3, $count);
    }
}
