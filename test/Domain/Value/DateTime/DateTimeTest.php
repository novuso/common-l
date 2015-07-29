<?php

namespace Novuso\Test\Common\Domain\Value\DateTime;

use DateTime as NativeDateTime;
use DateTimeZone;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Value\DateTime\DateTime
 */
class DateTimeTest extends PHPUnit_Framework_TestCase
{
    public function test_that_create_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame('2015-06-20T16:30:06.032401[America/Chicago]', $dateTime->toString());
    }

    public function test_that_now_returns_expected_instance()
    {
        $native = new NativeDateTime('now', new DateTimeZone('America/Chicago'));
        $dateTime = DateTime::now('America/Chicago');
        $this->assertSame($native->format('Y-m-d'), $dateTime->format('Y-m-d'));
    }

    public function test_that_from_native_returns_expected_instance()
    {
        $string = '2015-06-20T16:30:06.032401';
        $native = NativeDateTime::createFromFormat('Y-m-d\TH:i:s.u', $string, new DateTimeZone('America/Chicago'));
        $dateTime = DateTime::fromNative($native);
        $this->assertSame('2015-06-20T16:30:06.032401[America/Chicago]', $dateTime->toString());
    }

    public function test_that_from_timestamp_returns_expected_instance()
    {
        $dateTime = DateTime::fromTimestamp(1434835806, 32401, 'America/Chicago');
        $this->assertSame('2015-06-20T16:30:06.032401[America/Chicago]', $dateTime->toString());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $dateTimeString = '2015-06-20T16:30:06.032401[America/Chicago]';
        $dateTime = DateTime::fromString($dateTimeString);
        $this->assertSame($dateTimeString, $dateTime->toString());
    }

    public function test_that_locale_format_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
            $this->assertSame('June', $dateTime->localeFormat('%B'));
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_date_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame('2015-06-20', $dateTime->date()->toString());
    }

    public function test_that_time_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame('16:30:06.032401', $dateTime->time()->toString());
    }

    public function test_that_timezone_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame('America/Chicago', $dateTime->timezone()->toString());
    }

    public function test_that_timezone_offset_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(-18000, $dateTime->timezoneOffset());
    }

    public function test_that_year_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(2015, $dateTime->year());
    }

    public function test_that_month_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(6, $dateTime->month());
    }

    public function test_that_month_name_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
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
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
            $this->assertSame('Jun', $dateTime->monthAbbr());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_day_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(20, $dateTime->day());
    }

    public function test_that_hour_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(16, $dateTime->hour());
    }

    public function test_that_minute_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(30, $dateTime->minute());
    }

    public function test_that_second_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(6, $dateTime->second());
    }

    public function test_that_micro_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(32401, $dateTime->micro());
    }

    public function test_that_week_day_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(6, $dateTime->weekDay());
    }

    public function test_that_week_day_name_returns_expected_value()
    {
        if (setlocale(LC_TIME, 'en_US.utf8') === 'en_US.utf8') {
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
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
            $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
            $this->assertSame('Sat', $dateTime->weekDayAbbr());
            setlocale(LC_TIME, '');
        } else {
            setlocale(LC_TIME, '');
            $this->markTestSkipped('Unable to set locale to en_US.utf8');
        }
    }

    public function test_that_timestamp_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(1434835806, $dateTime->timestamp());
    }

    public function test_that_day_of_year_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(170, $dateTime->dayOfYear());
    }

    public function test_that_week_number_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(25, $dateTime->weekNumber());
    }

    public function test_that_days_in_month_returns_expected_value()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(30, $dateTime->daysInMonth());
    }

    public function test_that_is_leap_year_returns_true_when_in_leap_year()
    {
        $dateTime = DateTime::create(2016, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertTrue($dateTime->isLeapYear());
    }

    public function test_that_is_leap_year_returns_false_when_not_in_leap_year()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertFalse($dateTime->isLeapYear());
    }

    public function test_that_is_daylight_savings_returns_false_when_in_standard_time()
    {
        $dateTime = DateTime::create(2015, 11, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertFalse($dateTime->isDaylightSavings());
    }

    public function test_that_is_daylight_savings_returns_true_when_in_daylight_savings()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertTrue($dateTime->isDaylightSavings());
    }

    public function test_that_to_native_returns_expected_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $native = $dateTime->toNative();
        $this->assertSame('2015-06-20T16:30:06.032401-05:00', $native->format('Y-m-d\TH:i:s.uP'));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $dateTime = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(0, $dateTime->compareTo($dateTime));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(0, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_greater_timestamp()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 7, 32401, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_timestamp()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 7, 32401, 'America/Chicago');
        $this->assertSame(-1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_greater_micro()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 32402, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_micro()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 32402, 'America/Chicago');
        $this->assertSame(-1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_one_for_greater_timezone_string_compare()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Los_Angeles');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $this->assertSame(1, $dateTime1->compareTo($dateTime2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_timezone_string_compare()
    {
        $dateTime1 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Chicago');
        $dateTime2 = DateTime::create(2015, 6, 20, 16, 30, 6, 32401, 'America/Los_Angeles');
        $this->assertSame(-1, $dateTime1->compareTo($dateTime2));
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_from_string_throws_exception_for_invalid_format()
    {
        DateTime::fromString('2015-06-20T16:30:06.032401-05:00');
    }
}
