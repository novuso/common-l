<?php

namespace Novuso\Common\Domain\Model\DateTime;

use DateTime as NativeDateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * DateTime represents a specific date and time
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class DateTime extends ValueObject implements Comparable
{
    /**
     * String format
     *
     * @var string
     */
    const STRING_FORMAT = 'Y-m-d\TH:i:s.u';

    /**
     * Date
     *
     * @var Date
     */
    protected $date;

    /**
     * Time
     *
     * @var Time
     */
    protected $time;

    /**
     * Timezone
     *
     * @var Timezone
     */
    protected $timezone;

    /**
     * Native DateTime
     *
     * @var DateTimeInterface|null
     */
    protected $dateTime;

    /**
     * Constructs DateTime
     *
     * @internal
     *
     * @param Date     $date     The date
     * @param Time     $time     The time
     * @param Timezone $timezone The timezone
     */
    private function __construct(Date $date, Time $time, Timezone $timezone)
    {
        $this->date = $date;
        $this->time = $time;
        $this->timezone = $timezone;
    }

    /**
     * Creates instance from date and time values
     *
     * @param int         $year     The year
     * @param int         $month    The month
     * @param int         $day      The day
     * @param int         $hour     The hour
     * @param int         $minute   The minute
     * @param int         $second   The second
     * @param int         $micro    The microsecond
     * @param string|null $timezone The timezone string or null for default
     *
     * @return DateTime
     *
     * @throws DomainException When the date/time is not valid
     */
    public static function create($year, $month, $day, $hour, $minute, $second, $micro, $timezone = null)
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : (string) $timezone;
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        return new self(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second, $micro),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance for the current date and time
     *
     * @param string|null $timezone The timezone or null for default
     *
     * @return DateTime
     */
    public static function now($timezone = null)
    {
        $time = sprintf('%.6f', microtime(true));
        $timezone = ($timezone === null) ? date_default_timezone_get() : (string) $timezone;
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = DateTimeImmutable::createFromFormat('U.u', $time, new DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $micro = (int) $dateTime->format('u');

        return new self(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second, $micro),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance from a native DateTime
     *
     * @param DateTimeInterface $dateTime A DateTimeInterface instance
     *
     * @return DateTime
     */
    public static function fromNative(DateTimeInterface $dateTime)
    {
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $micro = (int) $dateTime->format('u');
        $timezone = $dateTime->getTimezone();

        return new self(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second, $micro),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance from a timestamp and timezone
     *
     * @param int         $timestamp The timestamp
     * @param int         $micro     The microsecond
     * @param string|null $timezone  The timezone string or null for default
     *
     * @return DateTime
     */
    public static function fromTimestamp($timestamp, $micro = 0, $timezone = null)
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : (string) $timezone;
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $time = sprintf('%d.%06d', (int) $timestamp, (int) $micro);
        $dateTime = DateTimeImmutable::createFromFormat('U.u', $time, new DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $micro = (int) $dateTime->format('u');

        return new self(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second, $micro),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates instance from a date/time string
     *
     * @param string $dateTime The date/time string
     *
     * @return DateTime
     *
     * @throws DomainException When the string is invalid
     */
    public static function fromString($dateTime)
    {
        assert(Test::isString($dateTime), sprintf(
            '%s expects $dateTime to be a string; received (%s) %s',
            __METHOD__,
            gettype($dateTime),
            VarPrinter::toString($dateTime)
        ));

        $pattern = sprintf(
            '/\A%s-%s-%sT%s:%s:%s\.%s\[%s\]\z/',
            '(?P<year>[\d]{4})',
            '(?P<month>[\d]{2})',
            '(?P<day>[\d]{2})',
            '(?P<hour>[\d]{2})',
            '(?P<minute>[\d]{2})',
            '(?P<second>[\d]{2})',
            '(?P<micro>[\d]{6})',
            '(?P<timezone>.+)'
        );
        if (!preg_match($pattern, $dateTime, $matches)) {
            $message = sprintf('%s expects $dateTime in "Y-m-d\TH:i:s.u[timezone]" format', __METHOD__);
            throw DomainException::create($message);
        }

        $year = (int) $matches['year'];
        $month = (int) $matches['month'];
        $day = (int) $matches['day'];
        $hour = (int) $matches['hour'];
        $minute = (int) $matches['minute'];
        $second = (int) $matches['second'];
        $micro = (int) $matches['micro'];
        $timezone = $matches['timezone'];

        return new self(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second, $micro),
            Timezone::create($timezone)
        );
    }

    /**
     * Retrieves formatted string representation
     *
     * Returns string formatted according to PHP date() function.
     *
     * @see http://php.net/date PHP date function
     *
     * @param string $format The format string
     *
     * @return string
     */
    public function format($format)
    {
        return $this->dateTime()->format((string) $format);
    }

    /**
     * Retrieves localized formatted string representation
     *
     * Returns string formatted according to PHP strftime() function. Set the
     * current locale using the setlocale() function.
     *
     * @see http://php.net/strftime PHP strftime function
     * @see http://php.net/setlocale PHP setlocale function
     *
     * @param string $format The format string
     *
     * @return string
     */
    public function localeFormat($format)
    {
        // http://php.net/manual/en/function.strftime.php#refsect1-function.strftime-examples
        // Example #3 Cross platform compatible example using the %e modifier
        // @codeCoverageIgnoreStart
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', (string) $format);
        }
        // @codeCoverageIgnoreEnd

        return strftime((string) $format, $this->timestamp());
    }

    /**
     * Retrieves ISO-8601 string representation
     *
     * @return string
     */
    public function iso8601()
    {
        return $this->format('Y-m-d\TH:i:sP');
    }

    /**
     * Retrieves the date
     *
     * @return Date
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * Retrieves the time
     *
     * @return Time
     */
    public function time()
    {
        return $this->time;
    }

    /**
     * Retrieves the timezone
     *
     * @return Timezone
     */
    public function timezone()
    {
        return $this->timezone;
    }

    /**
     * Retrieves the timezone offset in seconds
     *
     * The offset for timezones west of UTC is always negative, and for those
     * east of UTC is always positive.
     *
     * @return int
     */
    public function timezoneOffset()
    {
        return (int) $this->format('Z');
    }

    /**
     * Retrieves the year
     *
     * @return int
     */
    public function year()
    {
        return $this->date->year();
    }

    /**
     * Retrieves the month
     *
     * @return int
     */
    public function month()
    {
        return $this->date->month();
    }

    /**
     * Retrieves the month name
     *
     * Set the current locale using the setlocale() function.
     *
     * @see http://php.net/setlocale PHP setlocale function
     *
     * @return string
     */
    public function monthName()
    {
        return strftime('%B', $this->timestamp());
    }

    /**
     * Retrieves the month abbreviation
     *
     * Set the current locale using the setlocale() function.
     *
     * @see http://php.net/setlocale PHP setlocale function
     *
     * @return string
     */
    public function monthAbbr()
    {
        return strftime('%b', $this->timestamp());
    }

    /**
     * Retrieves the day
     *
     * @return int
     */
    public function day()
    {
        return $this->date->day();
    }

    /**
     * Retrieves the hour
     *
     * @return int
     */
    public function hour()
    {
        return $this->time->hour();
    }

    /**
     * Retrieves the minute
     *
     * @return int
     */
    public function minute()
    {
        return $this->time->minute();
    }

    /**
     * Retrieves the second
     *
     * @return int
     */
    public function second()
    {
        return $this->time->second();
    }

    /**
     * Retrieves the microsecond
     *
     * @return int
     */
    public function micro()
    {
        return $this->time->micro();
    }

    /**
     * Retrieves the week day
     *
     * From 0 for Sunday to 6 for Saturday.
     *
     * @return int
     */
    public function weekDay()
    {
        return (int) $this->format('w');
    }

    /**
     * Retrieves the week day name
     *
     * Set the current locale using the setlocale() function.
     *
     * @see http://php.net/setlocale PHP setlocale function
     *
     * @return string
     */
    public function weekDayName()
    {
        return strftime('%A', $this->timestamp());
    }

    /**
     * Retrieves the week day abbreviation
     *
     * Set the current locale using the setlocale() function.
     *
     * @see http://php.net/setlocale PHP setlocale function
     *
     * @return string
     */
    public function weekDayAbbr()
    {
        return strftime('%a', $this->timestamp());
    }

    /**
     * Retrieves seconds since the Unix Epoch
     *
     * @return int
     */
    public function timestamp()
    {
        return $this->dateTime()->getTimestamp();
    }

    /**
     * Retrieves the day of the year
     *
     * Days are numbered starting with 0.
     *
     * @return int
     */
    public function dayOfYear()
    {
        return (int) $this->format('z');
    }

    /**
     * Retrieves ISO-8601 week number of the year
     *
     * @return int
     */
    public function weekNumber()
    {
        return (int) $this->format('W');
    }

    /**
     * Retrieves the number of days in the month
     *
     * @return int
     */
    public function daysInMonth()
    {
        return (int) $this->format('t');
    }

    /**
     * Checks if the year is a leap year
     *
     * @return bool
     */
    public function isLeapYear()
    {
        if ($this->format('L') == '1') {
            return true;
        }

        return false;
    }

    /**
     * Checks if the date is in daylight savings time
     *
     * @return bool
     */
    public function isDaylightSavings()
    {
        if ($this->format('I') == '1') {
            return true;
        }

        return false;
    }

    /**
     * Retrieves a native DateTime instance
     *
     * @return DateTimeInterface
     */
    public function toNative()
    {
        $time = sprintf('%d.%06d', $this->timestamp(), $this->micro());
        $dateTime = NativeDateTime::createFromFormat('U.u', $time, new DateTimeZone('UTC'));
        $dateTime->setTimezone(new DateTimeZone($this->timezone->toString()));

        return $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s[%s]', $this->format(self::STRING_FORMAT), $this->timezone->toString());
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object)
    {
        if ($this === $object) {
            return 0;
        }

        assert(
            Test::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

        $thisStamp = $this->timestamp();
        $thatStamp = $object->timestamp();

        if ($thisStamp > $thatStamp) {
            return 1;
        }
        if ($thisStamp < $thatStamp) {
            return -1;
        }

        $thisMicro = $this->micro();
        $thatMicro = $object->micro();

        if ($thisMicro > $thatMicro) {
            return 1;
        }
        if ($thisMicro < $thatMicro) {
            return -1;
        }

        return $this->timezone->compareTo($object->timezone);
    }

    /**
     * Retrieves a native DateTime instance
     *
     * @return DateTimeInterface
     */
    private function dateTime()
    {
        if ($this->dateTime === null) {
            $year = $this->year();
            $month = $this->month();
            $day = $this->day();
            $hour = $this->hour();
            $minute = $this->minute();
            $second = $this->second();
            $micro = $this->micro();
            $timezone = $this->timezone()->toString();
            $this->dateTime = self::createNative($year, $month, $day, $hour, $minute, $second, $micro, $timezone);
        }

        return $this->dateTime;
    }

    /**
     * Creates a native DateTime from date and time values
     *
     * @param int    $year     The year
     * @param int    $month    The month
     * @param int    $day      The day
     * @param int    $hour     The hour
     * @param int    $minute   The minute
     * @param int    $second   The second
     * @param int    $micro    The microsecond
     * @param string $timezone The timezone
     *
     * @return DateTimeInterface
     */
    private static function createNative($year, $month, $day, $hour, $minute, $second, $micro, $timezone)
    {
        $time = sprintf('%04d-%02d-%02dT%02d:%02d:%02d.%06d', $year, $month, $day, $hour, $minute, $second, $micro);

        return DateTimeImmutable::createFromFormat(self::STRING_FORMAT, $time, new DateTimeZone($timezone));
    }
}
