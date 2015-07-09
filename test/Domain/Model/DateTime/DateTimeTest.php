<?php

namespace Novuso\Test\Common\Domain\Model\DateTime;

use DateTime as NativeDateTime;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\DateTime\DateTime
 */
class DateTimeTest extends PHPUnit_Framework_TestCase
{
    public function test_that_now_accepts_timezone_argument()
    {
        $dateTime = DateTime::now('America/Chicago');
        $this->assertSame('America/Chicago', (string) $dateTime->timezone());
    }

    public function test_that_create_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12, 'America/Chicago');
        $this->assertSame('2015-06-20 16:30:12 America/Chicago', $dateTime->toString());
    }

    public function test_that_from_native_returns_expected_instance()
    {
        $native = new NativeDateTime('now');
        $dateTime = DateTime::fromNative($native);
        $this->assertSame($native->format('Y-m-d H:i:s e'), $dateTime->toString());
    }

    public function test_that_from_timestamp_returns_expected_instance()
    {
        $native = new NativeDateTime('now');
        $timestamp = $native->getTimestamp();
        $dateTime = DateTime::fromTimestamp($timestamp);
        $this->assertSame($native->format('Y-m-d H:i:s e'), $dateTime->toString());
    }

    public function test_that_format_functions_like_native_date_time()
    {
        $native = new NativeDateTime('now');
        $dateTime = DateTime::now();
        $this->assertSame($native->format('Y-m-d'), $dateTime->format('Y-m-d'));
    }

    public function test_that_locale_format_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
            $this->assertSame('June', $dateTime->localeFormat('%B'));
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_week_day_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
        $this->assertSame(6, $dateTime->weekDay());
    }

    public function test_that_timezone_offset_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12, 'America/Chicago');
        $this->assertSame(-18000, $dateTime->timezoneOffset());
    }

    public function test_that_month_name_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
            $this->assertSame('June', $dateTime->monthName());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_month_abbr_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
            $this->assertSame('Jun', $dateTime->monthAbbr());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_week_day_name_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
            $this->assertSame('Saturday', $dateTime->weekDayName());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_week_day_abbr_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
            $this->assertSame('Sat', $dateTime->weekDayAbbr());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_day_of_year_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
        $this->assertSame(170, $dateTime->dayOfYear());
    }

    public function test_that_week_number_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
        $this->assertSame(25, $dateTime->weekNumber());
    }

    public function test_that_days_in_month_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
        $this->assertSame(30, $dateTime->daysInMonth());
    }

    public function test_that_is_leap_year_returns_true_when_in_leap_year()
    {
        $dateTime = DateTime::create(2016, 6, 20, 16, 30, 12);
        $this->assertTrue($dateTime->isLeapYear());
    }

    public function test_that_is_leap_year_returns_false_when_not_in_leap_year()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12);
        $this->assertFalse($dateTime->isLeapYear());
    }

    public function test_that_is_daylight_savings_returns_false_when_in_standard_time()
    {
        $dateTime = DateTime::create(2015, 11, 20, 16, 30, 12, 'America/Chicago');
        $this->assertFalse($dateTime->isDaylightSavings());
    }

    public function test_that_is_daylight_savings_returns_true_when_in_daylight_savings()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 12, 'America/Chicago');
        $this->assertTrue($dateTime->isDaylightSavings());
    }

    public function test_that_to_native_returns_native_date_time_instance()
    {
        $dateTime = DateTime::now();
        $this->assertInstanceOf('DateTimeInterface', $dateTime->toNative());
    }

    public function test_that_it_is_json_encodable()
    {
        $dateTime = DateTime::create(2015, 11, 20, 16, 30, 12, 'America/Chicago');
        $data = ['published' => $dateTime];
        $this->assertSame('{"published":"2015-11-20T16:30:12-06:00"}', json_encode($data));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $dateTime = DateTime::create(2015, 11, 20, 16, 30, 12, 'America/Chicago');
        $this->assertSame(0, $dateTime->compareTo($dateTime));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $dateTime1 = DateTime::create(2015, 11, 20, 16, 30, 12, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 11, 20, 16, 30, 12, 'America/Chicago');
        $this->assertSame(0, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_greater_timestamp()
    {
        $dateTime1 = DateTime::create(2015, 11, 20, 16, 30, 13, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 11, 20, 16, 30, 12, 'America/Chicago');
        $this->assertSame(1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_lesser_timestamp()
    {
        $dateTime1 = DateTime::create(2015, 11, 20, 16, 30, 12, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 11, 20, 16, 30, 13, 'America/Chicago');
        $this->assertSame(-1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_greater_timezone_string_compare()
    {
        $dateTime1 = DateTime::create(2015, 11, 20, 14, 30, 12, 'America/Los_Angeles');
        $dateTime2 = DateTime::create(2015, 11, 20, 16, 30, 12, 'America/Chicago');
        $this->assertSame(1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_lesser_timezone_string_compare()
    {
        $dateTime1 = DateTime::create(2015, 11, 20, 16, 30, 12, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 11, 20, 14, 30, 12, 'America/Los_Angeles');
        $this->assertSame(-1, $dateTime1->compareTo($dateTime2));
    }

    /**
     * @expectedException AssertionError
     */
    public function test_that_now_triggers_assert_error_for_invalid_timezone()
    {
        DateTime::now('Universal');
    }
}
