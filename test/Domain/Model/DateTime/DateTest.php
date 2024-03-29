<?php

namespace Novuso\Test\Common\Domain\Model\DateTime;

use DateTime as NativeDateTime;
use DateTimeZone;
use Novuso\Common\Domain\Model\DateTime\Date;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\DateTime\Date
 */
class DateTest extends PHPUnit_Framework_TestCase
{
    public function test_that_create_returns_expected_instance()
    {
        $date = Date::create(2015, 6, 20);
        $this->assertSame('2015-06-20', $date->toString());
    }

    public function test_that_now_returns_expected_instance()
    {
        $dateTime = new NativeDateTime('now', new DateTimeZone('America/Chicago'));
        $date = Date::now('America/Chicago');
        $this->assertSame($dateTime->format('Y-m-d'), $date->toString());
    }

    public function test_that_from_native_returns_expected_instance()
    {
        $dateTime = new NativeDateTime('now', new DateTimeZone('America/Chicago'));
        $date = Date::fromNative($dateTime);
        $this->assertSame($dateTime->format('Y-m-d'), $date->toString());
    }

    public function test_that_from_timestamp_returns_expected_instance()
    {
        $date = Date::fromTimestamp(1434835806, 'America/Chicago');
        $this->assertSame('2015-06-20', $date->toString());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $dateString = '2015-06-20';
        $date = Date::fromString($dateString);
        $this->assertSame($dateString, $date->toString());
    }

    public function test_that_it_is_json_encodable()
    {
        $dateString = '2015-06-20';
        $date = Date::fromString($dateString);
        $data = ['date' => $date];
        $this->assertSame('{"date":"2015-06-20"}', json_encode($data));
    }

    public function test_that_it_is_serializable()
    {
        $dateString = '2015-06-20';
        $string = serialize(Date::fromString($dateString));
        $date = unserialize($string);
        $this->assertSame($dateString, $date->toString());
    }

    public function test_that_year_returns_expected_value()
    {
        $date = Date::create(2015, 6, 20);
        $this->assertSame(2015, $date->year());
    }

    public function test_that_month_returns_expected_value()
    {
        $date = Date::create(2015, 6, 20);
        $this->assertSame(6, $date->month());
    }

    public function test_that_day_returns_expected_value()
    {
        $date = Date::create(2015, 6, 20);
        $this->assertSame(20, $date->day());
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $date = Date::create(2015, 6, 20);
        $this->assertSame(0, $date->compareTo($date));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $date1 = Date::create(2015, 6, 20);
        $date2 = Date::create(2015, 6, 20);
        $this->assertSame(0, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_one_for_greater_year()
    {
        $date1 = Date::create(2016, 6, 20);
        $date2 = Date::create(2015, 6, 20);
        $this->assertSame(1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_year()
    {
        $date1 = Date::create(2015, 6, 20);
        $date2 = Date::create(2016, 6, 20);
        $this->assertSame(-1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_one_for_greater_month()
    {
        $date1 = Date::create(2015, 7, 20);
        $date2 = Date::create(2015, 6, 20);
        $this->assertSame(1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_month()
    {
        $date1 = Date::create(2015, 6, 20);
        $date2 = Date::create(2015, 7, 20);
        $this->assertSame(-1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_one_for_greater_day()
    {
        $date1 = Date::create(2015, 6, 21);
        $date2 = Date::create(2015, 6, 20);
        $this->assertSame(1, $date1->compareTo($date2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_day()
    {
        $date1 = Date::create(2015, 6, 20);
        $date2 = Date::create(2015, 6, 21);
        $this->assertSame(-1, $date1->compareTo($date2));
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_create_throws_exception_for_invalid_date()
    {
        Date::create(2015, 2, 30);
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_from_string_throws_exception_for_invalid_format()
    {
        Date::fromString('06-20-2015');
    }
}
