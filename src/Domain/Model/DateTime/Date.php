<?php

namespace Novuso\Common\Domain\Model\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * Date represents a calendar date
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class Date extends ValueObject implements Comparable
{
    /**
     * Year
     *
     * @var int
     */
    protected $year;

    /**
     * Month
     *
     * @var int
     */
    protected $month;

    /**
     * Day
     *
     * @var int
     */
    protected $day;

    /**
     * Constructs Date
     *
     * @internal
     *
     * @param int $year  The year
     * @param int $month The month
     * @param int $day   The day
     *
     * @throws TypeException When argument types are invalid
     * @throws DomainException When the date is not valid
     */
    private function __construct($year, $month, $day)
    {
        $this->guardTypes($year, $month, $day);
        $this->guardDate($year, $month, $day);

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * Creates instance from date values
     *
     * @param int $year  The year
     * @param int $month The month
     * @param int $day   The day
     *
     * @return Date
     *
     * @throws TypeException When argument types are invalid
     * @throws DomainException When the date is not valid
     */
    public static function create($year, $month, $day)
    {
        return new self($year, $month, $day);
    }

    /**
     * Creates instance for the current date
     *
     * @param string|null $timezone The timezone string or null for default
     *
     * @return Date
     */
    public static function now($timezone = null)
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : (string) $timezone;
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        return new self($year, $month, $day);
    }

    /**
     * Creates an instance from a native DateTime
     *
     * @param DateTimeInterface $dateTime A DateTimeInterface instance
     *
     * @return Date
     */
    public static function fromNative(DateTimeInterface $dateTime)
    {
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        return new self($year, $month, $day);
    }

    /**
     * Creates an instance from a timestamp and timezone
     *
     * @param int         $timestamp The timestamp
     * @param string|null $timezone  The timezone string or null for default
     *
     * @return Date
     */
    public static function fromTimestamp($timestamp, $timezone = null)
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : (string) $timezone;
        assert(Test::isTimezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $time = sprintf('%d', (int) $timestamp);
        $dateTime = DateTimeImmutable::createFromFormat('U', $time, new DateTimeZone('UTC'));
        $dateTime = $dateTime->setTimezone(new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        return new self($year, $month, $day);
    }

    /**
     * Creates instance from formatted date string
     *
     * @param string $date The date string
     *
     * @return Date
     *
     * @throws TypeException When date is not a string
     * @throws DomainException When the date is not valid
     */
    public static function fromString($date)
    {
        if (!is_string($date)) {
            $message = sprintf(
                '%s expects $date to be a string; received (%s) %s',
                __METHOD__,
                gettype($date),
                VarPrinter::toString($date)
            );
            throw TypeException::create($message);
        }

        $pattern = '/\A(?P<year>[\d]{4})-(?P<month>[\d]{2})-(?P<day>[\d]{2})\z/';
        if (!preg_match($pattern, $date, $matches)) {
            $message = sprintf('%s expects $date in "Y-m-d" format', __METHOD__);
            throw DomainException::create($message);
        }

        $year = (int) $matches['year'];
        $month = (int) $matches['month'];
        $day = (int) $matches['day'];

        return new self($year, $month, $day);
    }

    /**
     * Retrieves the year
     *
     * @return int
     */
    public function year()
    {
        return $this->year;
    }

    /**
     * Retrieves the month
     *
     * @return int
     */
    public function month()
    {
        return $this->month;
    }

    /**
     * Retrieves the day
     *
     * @return int
     */
    public function day()
    {
        return $this->day;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
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
     * Validates argument types
     *
     * @param int $year  The year
     * @param int $month The month
     * @param int $day   The day
     *
     * @return void
     *
     * @throws TypeException When argument types are invalid
     */
    private function guardTypes($year, $month, $day)
    {
        if (!is_int($year)) {
            $message = sprintf(
                '%s::__construct expects $year to be an integer; received (%s) %s',
                static::class,
                gettype($year),
                VarPrinter::toString($year)
            );
            throw TypeException::create($message);
        }

        if (!is_int($month)) {
            $message = sprintf(
                '%s::__construct expects $month to be an integer; received (%s) %s',
                static::class,
                gettype($month),
                VarPrinter::toString($month)
            );
            throw TypeException::create($message);
        }

        if (!is_int($day)) {
            $message = sprintf(
                '%s::__construct expects $day to be an integer; received (%s) %s',
                static::class,
                gettype($day),
                VarPrinter::toString($day)
            );
            throw TypeException::create($message);
        }
    }

    /**
     * Validates the date
     *
     * @param int $year  The year
     * @param int $month The month
     * @param int $day   The day
     *
     * @return void
     *
     * @throws DomainException When the date is not valid
     */
    private function guardDate($year, $month, $day)
    {
        if (!checkdate($month, $day, $year)) {
            $message = sprintf('Invalid date: %04d-%02d-%02d', $year, $month, $day);
            throw DomainException::create($message);
        }
    }
}
