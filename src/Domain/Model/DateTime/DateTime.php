<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
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
 * @version   0.0.0
 */
final class DateTime extends ValueObject implements Comparable
{
    /**
     * String format
     *
     * @var string
     */
    const STRING_FORMAT = 'Y-m-d H:i:s e';

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
     * @var DateTimeInterface
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
     * @param string|null $timezone The timezone string or null for default
     *
     * @return DateTime
     *
     * @throws DomainException When the arguments are invalid
     */
    public static function create(
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute,
        int $second,
        string $timezone = null
    ): DateTime {
        $timezone = ($timezone === null) ? date_default_timezone_get() : $timezone;
        assert(Test::timezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        return new self(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second),
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
    public static function now(string $timezone = null): DateTime
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : $timezone;
        assert(Test::timezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');

        return new self(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second),
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
    public static function fromNative(DateTimeInterface $dateTime): DateTime
    {
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $timezone = $dateTime->getTimezone();

        return new self(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second),
            Timezone::create($timezone)
        );
    }

    /**
     * Creates an instance from a timestamp and timezone
     *
     * @param int        $timestamp The timestamp
     * @param mixed|null $timezone  The timezone or null for default
     *
     * @return DateTime
     */
    public static function fromTimestamp(int $timestamp, string $timezone = null): DateTime
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : $timezone;
        assert(Test::timezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = new DateTimeImmutable(sprintf('@%d', $timestamp), new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');

        return new self(
            Date::create($year, $month, $day),
            Time::create($hour, $minute, $second),
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
    public function format(string $format): string
    {
        return $this->dateTime()->format($format);
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
    public function localeFormat(string $format): string
    {
        // http://php.net/manual/en/function.strftime.php#example-2510
        // Check for Windows to find and replace the %e modifier correctly
        // @codeCoverageIgnoreStart
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
        }
        // @codeCoverageIgnoreEnd

        return strftime($format, $this->timestamp());
    }

    /**
     * Retrieves the date
     *
     * @return Date
     */
    public function date(): Date
    {
        return $this->date;
    }

    /**
     * Retrieves the time
     *
     * @return Time
     */
    public function time(): Time
    {
        return $this->time;
    }

    /**
     * Retrieves the timezone
     *
     * @return Timezone
     */
    public function timezone(): Timezone
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
    public function timezoneOffset(): int
    {
        return (int) $this->format('Z');
    }

    /**
     * Retrieves the year
     *
     * @return int
     */
    public function year(): int
    {
        return $this->date->year();
    }

    /**
     * Retrieves the month
     *
     * @return int
     */
    public function month(): int
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
    public function monthName(): string
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
    public function monthAbbr(): string
    {
        return strftime('%b', $this->timestamp());
    }

    /**
     * Retrieves the day
     *
     * @return int
     */
    public function day(): int
    {
        return $this->date->day();
    }

    /**
     * Retrieves the hour
     *
     * @return int
     */
    public function hour(): int
    {
        return $this->time->hour();
    }

    /**
     * Retrieves the minute
     *
     * @return int
     */
    public function minute(): int
    {
        return $this->time->minute();
    }

    /**
     * Retrieves the second
     *
     * @return int
     */
    public function second(): int
    {
        return $this->time->second();
    }

    /**
     * Retrieves the week day
     *
     * From 0 for Sunday to 6 for Saturday.
     *
     * @return int
     */
    public function weekDay(): int
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
    public function weekDayName(): string
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
    public function weekDayAbbr(): string
    {
        return strftime('%a', $this->timestamp());
    }

    /**
     * Retrieves seconds since the Unix Epoch
     *
     * @return int
     */
    public function timestamp(): int
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
    public function dayOfYear(): int
    {
        return (int) $this->format('z');
    }

    /**
     * Retrieves ISO-8601 week number of the year
     *
     * @return int
     */
    public function weekNumber(): int
    {
        return (int) $this->format('W');
    }

    /**
     * Retrieves the number of days in the month
     *
     * @return int
     */
    public function daysInMonth(): int
    {
        return (int) $this->format('t');
    }

    /**
     * Checks if the year is a leap year
     *
     * @return bool
     */
    public function isLeapYear(): bool
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
    public function isDaylightSavings(): bool
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
    public function toNative(): DateTimeInterface
    {
        return $this->dateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->format(self::STRING_FORMAT);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): string
    {
        return $this->format(DATE_ATOM);
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object): int
    {
        if ($this === $object) {
            return 0;
        }

        assert(Test::sameType($this, $object), sprintf('Comparison requires instance of %s', static::class));

        $thisStamp = $this->timestamp();
        $thatStamp = $object->timestamp();

        if ($thisStamp > $thatStamp) {
            return 1;
        }
        if ($thisStamp < $thatStamp) {
            return -1;
        }

        return $this->timezone->compareTo($object->timezone());
    }

    /**
     * Retrieves a native DateTime instance
     *
     * @return DateTimeInterface
     */
    protected function dateTime(): DateTimeInterface
    {
        if ($this->dateTime === null) {
            $year = $this->year();
            $month = $this->month();
            $day = $this->day();
            $hour = $this->hour();
            $minute = $this->minute();
            $second = $this->second();
            $timezone = $this->timezone()->toString();
            $this->dateTime = self::createNative($year, $month, $day, $hour, $minute, $second, $timezone);
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
     * @param string $timezone The timezone
     *
     * @return DateTimeInterface
     */
    private static function createNative(
        int $year,
        int $month,
        int $day,
        int $hour,
        int $minute,
        int $second,
        string $timezone
    ): DateTimeInterface {
        $time = sprintf('%04d-%02d-%02dT%02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second);

        return new DateTimeImmutable($time, new DateTimeZone($timezone));
    }
}
