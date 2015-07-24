<?php

namespace Novuso\Test\Common\Domain\Value\DateTime;

use DateTimeZone;
use Novuso\Common\Domain\Value\DateTime\Timezone;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Value\DateTime\Timezone
 */
class TimezoneTest extends PHPUnit_Framework_TestCase
{
    public function test_that_constructor_takes_timezone_string_value()
    {
        $this->assertInstanceOf(Timezone::class, Timezone::create('America/Chicago'));
    }

    public function test_that_constructor_takes_timezone_instance_value()
    {
        $this->assertInstanceOf(Timezone::class, Timezone::create(Timezone::create('America/Chicago')));
    }

    public function test_that_constructor_takes_date_time_zone_value()
    {
        $this->assertInstanceOf(Timezone::class, Timezone::create(new DateTimeZone('America/Chicago')));
    }

    public function test_that_create_returns_expected_instance()
    {
        $timezone = Timezone::create('UTC');
        $this->assertSame('UTC', $timezone->toString());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $timezone = Timezone::fromString('America/Chicago');
        $this->assertSame('America/Chicago', $timezone->toString());
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $timezone = Timezone::create('America/Chicago');
        $this->assertSame(0, $timezone->compareTo($timezone));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $timezone1 = Timezone::create('America/Chicago');
        $timezone2 = Timezone::create('America/Chicago');
        $this->assertSame(0, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_one_for_greater_first_string_comparison()
    {
        $timezone1 = Timezone::create('Europe/Madrid');
        $timezone2 = Timezone::create('America/Chicago');
        $this->assertSame(1, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_first_string_comparison()
    {
        $timezone1 = Timezone::create('America/Chicago');
        $timezone2 = Timezone::create('Europe/Madrid');
        $this->assertSame(-1, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_one_for_greater_second_string_comparison()
    {
        $timezone1 = Timezone::create('America/Los_Angeles');
        $timezone2 = Timezone::create('America/Chicago');
        $this->assertSame(1, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_second_string_comparison()
    {
        $timezone1 = Timezone::create('America/Chicago');
        $timezone2 = Timezone::create('America/Los_Angeles');
        $this->assertSame(-1, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_zero_for_equal_third_string_comparison()
    {
        $timezone1 = Timezone::create('America/Indiana/Indianapolis');
        $timezone2 = Timezone::create('America/Indiana/Indianapolis');
        $this->assertSame(0, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_one_for_greater_third_string_comparison()
    {
        $timezone1 = Timezone::create('America/Indiana/Knox');
        $timezone2 = Timezone::create('America/Indiana/Indianapolis');
        $this->assertSame(1, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_third_string_comparison()
    {
        $timezone1 = Timezone::create('America/Indiana/Indianapolis');
        $timezone2 = Timezone::create('America/Indiana/Knox');
        $this->assertSame(-1, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_one_for_segmented_timezone_over_other_timezones()
    {
        $timezone1 = Timezone::create('America/Los_Angeles');
        $timezone2 = Timezone::create('UTC');
        $this->assertSame(1, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_neg_one_for_other_timezone_over_segmented_timezones()
    {
        $timezone1 = Timezone::create('UTC');
        $timezone2 = Timezone::create('America/Los_Angeles');
        $this->assertSame(-1, $timezone1->compareTo($timezone2));
    }

    public function test_that_compare_to_returns_zero_for_equal_single_string_comparison()
    {
        $timezone1 = Timezone::create('UTC');
        $timezone2 = Timezone::create('UTC');
        $this->assertSame(0, $timezone1->compareTo($timezone2));
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_constructor_throws_exception_for_invalid_timezone()
    {
        Timezone::create('Universal');
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_from_string_throws_exception_for_invalid_type()
    {
        Timezone::fromString(null);
    }
}
