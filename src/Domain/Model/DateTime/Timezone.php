<?php

namespace Novuso\Common\Domain\Model\DateTime;

use DateTimeZone;
use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * Timezone represents a time zone
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class Timezone extends ValueObject implements Comparable
{
    /**
     * Timezone value
     *
     * @var string
     */
    protected $value;

    /**
     * Constructs Timezone
     *
     * @internal
     *
     * @param mixed $value The timezone value
     *
     * @throws DomainException When the value is not a valid timezone
     */
    private function __construct($value)
    {
        if (!Test::isTimezone($value)) {
            $message = sprintf('Invalid timezone: %s', VarPrinter::toString($value));
            throw DomainException::create($message);
        }

        if ($value instanceof DateTimeZone) {
            $value = $value->getName();
        }

        $this->value = (string) $value;
    }

    /**
     * Creates instance from a timezone value
     *
     * @param mixed $value The timezone value
     *
     * @return Timezone
     *
     * @throws DomainException When the value is not a valid timezone
     */
    public static function create($value)
    {
        return new self($value);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return $this->value;
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

        $thisVal = $this->value;
        $thatVal = $object->value;

        $thisParts = explode('/', $thisVal);
        $thatParts = explode('/', $thatVal);

        if (count($thisParts) > 1 && count($thatParts) > 1) {
            return $this->compareParts($thisParts, $thatParts);
        } elseif (count($thisParts) > 1) {
            return 1;
        } elseif (count($thatParts) > 1) {
            return -1;
        }

        $comp = strnatcmp($thisVal, $thatVal);

        return ($comp > 0) ? 1 : ($comp < 0 ? -1 : 0);
    }

    /**
     * Compares two timezones by segments
     *
     * @param array $thisParts This parts
     * @param array $thatParts Other parts
     *
     * @return int
     */
    private function compareParts(array $thisParts, array $thatParts)
    {
        $compMajor = strnatcmp($thisParts[0], $thatParts[0]);
        if ($compMajor > 0) {
            return 1;
        }
        if ($compMajor < 0) {
            return -1;
        }
        $compMinor = strnatcmp($thisParts[1], $thatParts[1]);
        if ($compMinor > 0) {
            return 1;
        }
        if ($compMinor < 0) {
            return -1;
        }
        if (isset($thisParts[2]) && isset($thatParts[2])) {
            $compSub = strnatcmp($thisParts[2], $thatParts[2]);
            if ($compSub > 0) {
                return 1;
            }
            if ($compSub < 0) {
                return -1;
            }

            return 0;
        }

        return 0;
    }
}
