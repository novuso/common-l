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
 * Time represents the time of day
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
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
     * Constructs Time
     *
     * @internal
     *
     * @param int $hour   The hour
     * @param int $minute The minute
     * @param int $second The second
     *
     * @throws DomainException When the time is invalid
     */
    private function __construct(int $hour, int $minute, int $second)
    {
        self::guardTime($hour, $minute, $second);

        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    /**
     * Creates an instance from time values
     *
     * @param int $hour   The hour
     * @param int $minute The minute
     * @param int $second The second
     *
     * @return Time
     *
     * @throws DomainException When the time is invalid
     */
    public static function create(int $hour, int $minute, int $second): Time
    {
        return new self($hour, $minute, $second);
    }

    /**
     * Creates instance for the current time
     *
     * @param string|null $timezone The timezone string or null for default
     *
     * @return Time
     */
    public static function now(string $timezone = null): Time
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : $timezone;
        assert(Test::timezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');

        return new self($hour, $minute, $second);
    }

    /**
     * Creates an instance from a native DateTime
     *
     * @param DateTimeInterface $dateTime A DateTimeInterface instance
     *
     * @return Time
     */
    public static function fromNative(DateTimeInterface $dateTime): Time
    {
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');

        return new self($hour, $minute, $second);
    }

    /**
     * Creates an instance from a timestamp and timezone
     *
     * @param int         $timestamp The timestamp
     * @param string|null $timezone  The timezone string or null for default
     *
     * @return Time
     */
    public static function fromTimestamp(int $timestamp, string $timezone = null): Time
    {
        $timezone = ($timezone === null) ? date_default_timezone_get() : $timezone;
        assert(Test::timezone($timezone), sprintf('Invalid timezone: %s', $timezone));

        $dateTime = new DateTimeImmutable(sprintf('@%d', $timestamp), new DateTimeZone($timezone));
        $hour = (int) $dateTime->format('G');
        $minute = (int) $dateTime->format('i');
        $second = (int) $dateTime->format('s');

        return new self($hour, $minute, $second);
    }

    /**
     * Retrieves the hour
     *
     * @return int
     */
    public function hour(): int
    {
        return $this->hour;
    }

    /**
     * Retrieves the minute
     *
     * @return int
     */
    public function minute(): int
    {
        return $this->minute;
    }

    /**
     * Retrieves the second
     *
     * @return int
     */
    public function second(): int
    {
        return $this->second;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return sprintf(
            '%02d:%02d:%02d',
            $this->hour,
            $this->minute,
            $this->second
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
     * Validates the time
     *
     * @param int $hour   The hour
     * @param int $minute The minute
     * @param int $second The second
     *
     * @return void
     *
     * @throws DomainException When the time is invalid
     */
    private static function guardTime(int $hour, int $minute, int $second)
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
    }
}
