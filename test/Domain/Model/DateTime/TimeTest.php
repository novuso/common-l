<?php

namespace Novuso\Test\Common\Domain\Model\DateTime;

use DateTime as NativeDateTime;
use Novuso\Common\Domain\Model\DateTime\Time;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\DateTime\Time
 */
class TimeTest extends PHPUnit_Framework_TestCase
{
    public function test_that_now_accepts_timezone_argument()
    {
        $time = Time::now('America/Chicago');
        $this->assertInstanceOf(Time::class, $time);
    }

    public function test_that_create_returns_expected_instance()
    {
        $time = Time::create(16, 30, 12);
        $this->assertSame('16:30:12', $time->toString());
    }

    public function test_that_from_native_returns_expected_instance()
    {
        $dateTime = new NativeDateTime('now');
        $time = Time::fromNative($dateTime);
        $this->assertSame($dateTime->format('H:i:s'), $time->toString());
    }

    public function test_that_from_timestamp_returns_expected_instance()
    {
        $dateTime = new NativeDateTime('now');
        $timestamp = $dateTime->getTimestamp();
        $time = Time::fromTimestamp($timestamp);
        $this->assertSame($dateTime->format('H:i:s'), $time->toString());
    }

    public function test_that_it_is_json_encodable()
    {
        $startTime = Time::create(16, 30, 0);
        $data = ['startTime' => $startTime];
        $this->assertSame('{"startTime":"16:30:00"}', json_encode($data));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $time = Time::create(16, 30, 12);
        $this->assertSame(0, $time->compareTo($time));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $time1 = Time::create(16, 30, 12);
        $time2 = Time::create(16, 30, 12);
        $this->assertSame(0, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_one_for_greater_hour()
    {
        $time1 = Time::create(17, 30, 12);
        $time2 = Time::create(16, 30, 12);
        $this->assertSame(1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_hour()
    {
        $time1 = Time::create(16, 30, 12);
        $time2 = Time::create(17, 30, 12);
        $this->assertSame(-1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_one_for_greater_minute()
    {
        $time1 = Time::create(16, 31, 12);
        $time2 = Time::create(16, 30, 12);
        $this->assertSame(1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_minute()
    {
        $time1 = Time::create(16, 30, 12, 3106);
        $time2 = Time::create(16, 31, 12, 3106);
        $this->assertSame(-1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_one_for_greater_second()
    {
        $time1 = Time::create(16, 30, 13);
        $time2 = Time::create(16, 30, 12);
        $this->assertSame(1, $time1->compareTo($time2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_second()
    {
        $time1 = Time::create(16, 30, 12);
        $time2 = Time::create(16, 30, 13);
        $this->assertSame(-1, $time1->compareTo($time2));
    }

    /**
     * @expectedException AssertionError
     */
    public function test_that_now_triggers_assert_error_for_invalid_timezone()
    {
        Time::now('Universal');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_constructor_throws_exception_for_hour_out_of_range()
    {
        Time::create(24, 30, 12);
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_constructor_throws_exception_for_minute_out_of_range()
    {
        Time::create(16, -30, 12);
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_constructor_throws_exception_for_second_out_of_range()
    {
        Time::create(16, 30, 120);
    }
}
