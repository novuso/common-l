<?php

namespace Novuso\Common\Domain\Model\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * Time represents the time of day
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class Time extends ValueObject implements Comparable
{
    /**
     * Minimum hour
     *
     * @var int
     */
    const MIN_HOUR = 0;

    /**
     * Maximum hour
     *
     * @var int
     */
    const MAX_HOUR = 23;

    /**
     * Minimum minute
     *
     * @var int
     */
    const MIN_MINUTE = 0;

    /**
     * Maximum minute
     *
     * @var int
     */
    const MAX_MINUTE = 59;

    /**
     * Minimum second
     *
     * @var int
     */
    const MIN_SECOND = 0;

    /**
     * Maximum second
     *
     * @var int
     */
    const MAX_SECOND = 59;

    /**
     * Minimum microsecond
     *
     * @var int
     */
    const MIN_MICRO = 0;

    /**
     * Maximum microsecond
     *
     * @var int
     */
    const MAX_MICRO = 999999;

    /**
     * Hour
     *
     * @var int
     */
    protected $hour;

    /**
     * Minute
     *
     * @var int
     */
    protected $minute;

    /**
     * Second
     *
     * @var int
     */
    protected $second;

    /**
     * Microsecond
     *
     * @var int
     */
    protected $micro;

    /**
     * Constructs Time
     *
     * @internal
     *
     * @param int $hour   The hour
     * @param int $minute The minute
     * @param int $second The second
     * @param int $micro  The microsecond
     *
     * @throws DomainException When the time is not valid
     */
    private function __construct($hour, $minute, $second, $micro)
    {
        assert(Test::isInt($hour), sprintf(
            '%s expects $hour to be an integer; received (%s) %s',
            __METHOD__,
            gettype($hour),
            VarPrinter::toString($hour)
        ));

        assert(Test::isInt($minute), sprintf(
            '%s expects $minute to be an integer; received (%s) %s',
            __METHOD__,
            gettype($minute),
            VarPrinter::toString($minute)
        ));

        assert(Test::isInt($second), sprintf(
            '%s expects $second to be an integer; received (%s) %s',
            __METHOD__,
            gettype($second),
            VarPrinter::toString($second)
        ));

        assert(Test::isInt($micro), sprintf(
            '%s expects $micro to be an integer; received (%s) %s',
            __METHOD__,
            gettype($micro),
            VarPrinter::toString($micro)
        ));

        $this->guardTime($hour, $minute, $second, $micro);

        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->micro = $micro;
    }

    /**
     * Creates instance from time values
     *
     * @param int $hour   The hour
     * @param int $minute The minute
     * @param int $second The second
     * @param int $micro  The microsecond
     *
     * @return Time
     *
     * @throws DomainException When the time is not valid
     */
    public static function create($hour, $minute, $second, $micro)
    {
        return new self($hour, $minute, $second, $micro);
    }

    /**
     * Creates instance for the current time
     *
     * @param string|null $timezone The timezone string or null for default
     *
     * @return Time
     */
    public static function now($timezone = null)
    {
        $time = sprintf('%.6f', microtime(true));
        $timezone = ($timezone === null) ? date_default_timezone_get() : (string) $timezone;
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = DateTimeImmutable::createFromFormat('U.u', $time, new DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $micro = (int) $dateTime->format('u');

        return new self($hour, $minute, $second, $micro);
    }

    /**
     * Creates an instance from a native DateTime
     *
     * @param DateTimeInterface $dateTime A DateTimeInterface instance
     *
     * @return Time
     */
    public static function fromNative(DateTimeInterface $dateTime)
    {
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $micro = (int) $dateTime->format('u');

        return new self($hour, $minute, $second, $micro);
    }

    /**
     * Creates instance from a timestamp and timezone
     *
     * @param int         $timestamp The timestamp
     * @param int         $micro     The microsecond
     * @param string|null $timezone  The timezone string or null for default
     *
     * @return Date
     */
    public static function fromTimestamp($timestamp, $micro = 0, $timezone = null)
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : (string) $timezone;
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $time = sprintf('%d.%06d', (int) $timestamp, (int) $micro);
        $dateTime = DateTimeImmutable::createFromFormat('U.u', $time, new DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');
        $micro = (int) $dateTime->format('u');

        return new self($hour, $minute, $second, $micro);
    }

    /**
     * Creates instance from a time string
     *
     * @param string $time The time string
     *
     * @return Time
     *
     * @throws DomainException When the string is invalid
     */
    public static function fromString($time)
    {
        assert(Test::isString($time), sprintf(
            '%s expects $time to be a string; received (%s) %s',
            __METHOD__,
            gettype($time),
            VarPrinter::toString($time)
        ));

        $pattern = '/\A(?P<hour>[\d]{2}):(?P<minute>[\d]{2}):(?P<second>[\d]{2}).(?P<micro>[\d]{6})\z/';
        if (!preg_match($pattern, $time, $matches)) {
            $message = sprintf('%s expects $time in "H:i:s.u" format', __METHOD__);
            throw DomainException::create($message);
        }

        $hour = (int) $matches['hour'];
        $minute = (int) $matches['minute'];
        $second = (int) $matches['second'];
        $micro = (int) $matches['micro'];

        return new self($hour, $minute, $second, $micro);
    }

    /**
     * Retrieves the hour
     *
     * @return int
     */
    public function hour()
    {
        return $this->hour;
    }

    /**
     * Retrieves the minute
     *
     * @return int
     */
    public function minute()
    {
        return $this->minute;
    }

    /**
     * Retrieves the second
     *
     * @return int
     */
    public function second()
    {
        return $this->second;
    }

    /**
     * Retrieves the microsecond
     *
     * @return int
     */
    public function micro()
    {
        return $this->micro;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%02d:%02d:%02d.%06d', $this->hour, $this->minute, $this->second, $this->micro);
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

        $comp = strnatcmp($this->toString(), $object->toString());

        if ($comp > 0) {
            return 1;
        }
        if ($comp < 0) {
            return -1;
        }

        return 0;
    }

    /**
     * Validates the time
     *
     * @param int $hour   The hour
     * @param int $minute The minute
     * @param int $second The second
     * @param int $micro  The microsecond
     *
     * @return void
     *
     * @throws DomainException When the time is not valid
     */
    private function guardTime($hour, $minute, $second, $micro)
    {
        if ($hour < self::MIN_HOUR || $hour > self::MAX_HOUR) {
            $message = sprintf(
                'Hour (%d) out of range[%d, %d]',
                $hour,
                self::MIN_HOUR,
                self::MAX_HOUR
            );
            throw DomainException::create($message);
        }

        if ($minute < self::MIN_MINUTE || $minute > self::MAX_MINUTE) {
            $message = sprintf(
                'Minute (%d) out of range[%d, %d]',
                $minute,
                self::MIN_MINUTE,
                self::MAX_MINUTE
            );
            throw DomainException::create($message);
        }

        if ($second < self::MIN_SECOND || $second > self::MAX_SECOND) {
            $message = sprintf(
                'Second (%d) out of range[%d, %d]',
                $second,
                self::MIN_SECOND,
                self::MAX_SECOND
            );
            throw DomainException::create($message);
        }

        if ($micro < self::MIN_MICRO || $micro > self::MAX_MICRO) {
            $message = sprintf(
                'Microsecond (%d) out of range[%d, %d]',
                $micro,
                self::MIN_MICRO,
                self::MAX_MICRO
            );
            throw DomainException::create($message);
        }
    }
}
