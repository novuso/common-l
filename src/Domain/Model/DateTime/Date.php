<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model\DateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\{Test, VarPrinter};

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
     * @throws DomainException When the date is invalid
     */
    private function __construct(int $year, int $month, int $day)
    {
        self::guardDate($year, $month, $day);

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * Creates an instance from date values
     *
     * @param int $year  The year
     * @param int $month The month
     * @param int $day   The day
     *
     * @return Date
     *
     * @throws DomainException When the date is invalid
     */
    public static function create(int $year, int $month, int $day): Date
    {
        return new self($year, $month, $day);
    }

    /**
     * Creates instance for the current time
     *
     * @param string|null $timezone The timezone string or null for default
     *
     * @return Date
     */
    public static function now(string $timezone = null): Date
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : $timezone;
        assert(Test::timezone($timezone), sprintf('Invalid timezone: %s', $timezone));

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
    public static function fromNative(DateTimeInterface $dateTime): Date
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
    public static function fromTimestamp(int $timestamp, string $timezone = null): Date
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : $timezone;
        assert(Test::timezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = new DateTimeImmutable(sprintf('@%d', $timestamp), new DateTimeZone($timezone));
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        return new self($year, $month, $day);
    }

    /**
     * Retrieves the year
     *
     * @return int
     */
    public function year(): int
    {
        return $this->year;
    }

    /**
     * Retrieves the month
     *
     * @return int
     */
    public function month(): int
    {
        return $this->month;
    }

    /**
     * Retrieves the day
     *
     * @return int
     */
    public function day(): int
    {
        return $this->day;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf(
            '%04d-%02d-%02d',
            $this->year,
            $this->month,
            $this->day
        );
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): string
    {
        return $this->toString();
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

        $comp = strcmp($this->toString(), $object->toString());

        if ($comp > 0) {
            return 1;
        }
        if ($comp < 0) {
            return -1;
        }

        return 0;
    }

    /**
     * Validates a date
     *
     * @param int $year  The year
     * @param int $month The month
     * @param int $day   The day
     *
     * @return void
     *
     * @throws DomainException When the date is invalid
     */
    private static function guardDate(int $year, int $month, int $day)
    {
        if (!checkdate($month, $day, $year)) {
            $message = sprintf('Invalid date: %04d-%02d-%02d', $year, $month, $day);
            throw DomainException::create($message);
        }
    }
}
