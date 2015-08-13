<?php

namespace Novuso\Common\Domain\Model\Identifier;

use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * Uuid represents a universally unique identifier
 *
 * @see       http://tools.ietf.org/html/rfc4122
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
final class Uuid extends ValueObject implements Comparable
{
    /**
     * UUID regex pattern
     *
     * @var string
     */
    const UUID_PATTERN = '/\A([a-f0-9]{8})-([a-f0-9]{4})-([a-f0-9]{4})-([a-f0-9]{2})([a-f0-9]{2})-([a-f0-9]{12})\z/';

    /**
     * UUID hex pattern
     *
     * @var string
     */
    const UUID_HEX = '/\A([a-f0-9]{8})([a-f0-9]{4})([a-f0-9]{4})([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{12})\z/';

    /**
     * Variant reserved, NCS backward compatibility
     *
     * @var int
     */
    const VARIANT_RESERVED_NCS = 0b0;

    /**
     * Variant for RFC 4122 supported by this class
     *
     * @var int
     */
    const VARIANT_RFC_4122 = 0b10;

    /**
     * Variant reserved, Microsoft backward compatibility
     *
     * @var int
     */
    const VARIANT_RESERVED_MICROSOFT = 0b110;

    /**
     * Variant reserved for future definition
     *
     * @var int
     */
    const VARIANT_RESERVED_FUTURE = 0b111;

    /**
     * Version number for a time-based UUID
     *
     * @var int
     */
    const VERSION_TIME = 0b0001;

    /**
     * Version number for a DCE Security UUID
     *
     * @var int
     */
    const VERSION_DCE = 0b0010;

    /**
     * Version number for an MD5 name-based UUID
     *
     * @var int
     */
    const VERSION_MD5 = 0b0011;

    /**
     * Version number for a random or pseudo-random UUID
     *
     * @var int
     */
    const VERSION_RANDOM = 0b0100;

    /**
     * Version number for a sha1 name-based UUID
     *
     * @var int
     */
    const VERSION_SHA1 = 0b0101;

    /**
     * Version number for an unknown UUID type
     *
     * RFC 4122 defines a Nil UUID having all 128 bits set to zero. This
     * constant provides a semantic alternative to throwing an exception.
     *
     * @var int
     */
    const VERSION_UNKNOWN = 0b0;

    /**
     * Namespace for a fully-qualified domain name
     *
     * @var string
     */
    const NAMESPACE_DNS = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';

    /**
     * Namespace for a URL
     *
     * @var string
     */
    const NAMESPACE_URL = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';

    /**
     * Namespace for an ISO OID
     *
     * @var string
     */
    const NAMESPACE_OID = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';

    /**
     * Namespace for an X.500 DN in DER or a text output format
     *
     * @var string
     */
    const NAMESPACE_X500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';

    /**
     * Nil UUID
     *
     * @var string
     */
    const NIL = '00000000-0000-0000-0000-000000000000';

    /**
     * Low field of the timestamp
     *
     * @var string
     */
    protected $timeLow;

    /**
     * Middle field of the timestamp
     *
     * @var string
     */
    protected $timeMid;

    /**
     * High field of the timestamp multiplexed with version
     *
     * @var string
     */
    protected $timeHiAndVersion;

    /**
     * High field of the clock sequence multiplexed with variant
     *
     * @var string
     */
    protected $clockSeqHiAndReserved;

    /**
     * Low field of the timestamp
     *
     * @var string
     */
    protected $clockSeqLow;

    /**
     * Spatially unique node identifier
     *
     * @var string
     */
    protected $node;

    /**
     * Constructs Uuid
     *
     * @internal
     *
     * @param string $timeLow               The low field of the timestamp
     * @param string $timeMid               The mid field of the timestamp
     * @param string $timeHiAndVersion      The high field of the timestamp
     *                                      multiplexed with the version
     * @param string $clockSeqHiAndReserved The high field of the clock
     *                                      sequence multiplexed with variant
     * @param string $clockSeqLow           The low field of the clock sequence
     * @param string $node                  A spatially unique node identifier
     */
    private function __construct($timeLow, $timeMid, $timeHiAndVersion, $clockSeqHiAndReserved, $clockSeqLow, $node)
    {
        $this->timeLow = $timeLow;
        $this->timeMid = $timeMid;
        $this->timeHiAndVersion = $timeHiAndVersion;
        $this->clockSeqHiAndReserved = $clockSeqHiAndReserved;
        $this->clockSeqLow = $clockSeqLow;
        $this->node = $node;
    }

    /**
     * Creates a pseudo-random instance
     *
     * @return Uuid
     */
    public static function random()
    {
        return self::uuid4();
    }

    /**
     * Creates a sequential pseudo-random instance
     *
     * This variation is not covered by RFC 4122, but it should provide
     * performance increases when using UUIDs as primary keys. The timestamp
     * should cover most significant bits or least significant bits depending
     * on how the database orders GUID values.
     *
     * @param bool $msb Whether or not timestamp covers most significant bits
     *
     * @return Uuid
     */
    public static function comb($msb = true)
    {
        $hash = bin2hex(random_bytes(10));
        $time = explode(' ', microtime());
        $milliseconds = sprintf('%d%03d', $time[1], $time[0] * 1000);
        $timestamp = sprintf('%012x', $milliseconds);
        if ($msb) {
            $hex = $timestamp.$hash;
        } else {
            $hex = $hash.$timestamp;
        }

        return self::fromUnformatted($hex, self::VERSION_RANDOM);
    }

    /**
     * Creates a time-based instance
     *
     * If node, clock sequence, or timestamp values are not passed in, they
     * will be generated.
     *
     * Generating the node and clock sequence are not recommended for this UUID
     * version. The current timestamp can be retrieved in the correct format
     * from the Uuid::timestamp() static method.
     *
     * @see http://tools.ietf.org/html/rfc4122#section-4.1.5
     * @see http://tools.ietf.org/html/rfc4122#section-4.1.6
     *
     * @param string|null $node      The node ID or MAC address
     * @param int|null    $clockSeq  The clock sequence as a 14-bit integer
     * @param string|null $timestamp The timestamp
     *
     * @return Uuid
     *
     * @throws DomainException When clockSeq is not a 14-bit unsigned integer
     * @throws DomainException When the node is not 6 hexadecimal octets
     * @throws DomainException When timestamp is not 8 hexadecimal octets
     */
    public static function time($node = null, $clockSeq = null, $timestamp = null)
    {
        return self::uuid1($node, $clockSeq, $timestamp);
    }

    /**
     * Creates a named instance
     *
     * @param Uuid|string $namespace A valid UUID namespace
     * @param string      $name      A unique string within the namespace
     *
     * @return Uuid
     *
     * @throws DomainException When the namespace is not a valid UUID
     */
    public static function named($namespace, $name)
    {
        return self::uuid5($namespace, $name);
    }

    /**
     * Creates an md5 named instance
     *
     * @param Uuid|string $namespace A valid UUID namespace
     * @param string      $name      A unique string within the namespace
     *
     * @return Uuid
     *
     * @throws DomainException When the namespace is not a valid UUID
     */
    public static function md5($namespace, $name)
    {
        return self::uuid3($namespace, $name);
    }

    /**
     * Creates instance from a UUID string
     *
     * @param string $uuid The UUID string
     *
     * @return Uuid
     *
     * @throws DomainException When the string is not a valid UUID
     */
    public static function parse($uuid)
    {
        assert(Test::isString($uuid), sprintf(
            '%s expects $uuid to be a string; received (%s) %s',
            __METHOD__,
            gettype($uuid),
            VarPrinter::toString($uuid)
        ));

        $uuid = strtolower(str_replace(['urn:', 'uuid:', '{', '}'], '', $uuid));

        if (!preg_match(self::UUID_PATTERN, $uuid, $matches)) {
            $message = sprintf('Invalid UUID string: %s', $uuid);
            throw DomainException::create($message);
        }

        $timeLow = $matches[1];
        $timeMid = $matches[2];
        $timeHiAndVersion = $matches[3];
        $clockSeqHiAndReserved = $matches[4];
        $clockSeqLow = $matches[5];
        $node = $matches[6];

        return new self($timeLow, $timeMid, $timeHiAndVersion, $clockSeqHiAndReserved, $clockSeqLow, $node);
    }

    /**
     * Creates instance from a UUID string
     *
     * @param string $uuid The uuid string
     *
     * @return Uuid
     *
     * @throws DomainException When the string is not a valid UUID
     */
    public static function fromString($uuid)
    {
        return self::parse($uuid);
    }

    /**
     * Creates instance from a hexidecimal string
     *
     * @param string $hex The UUID hexidecimal string
     *
     * @return Uuid
     *
     * @throws DomainException When the hexidecimal string is not valid
     */
    public static function fromHex($hex)
    {
        assert(Test::isString($hex), sprintf(
            '%s expects $hex to be a string; received (%s) %s',
            __METHOD__,
            gettype($hex),
            VarPrinter::toString($hex)
        ));

        $hex = strtolower($hex);

        if (!preg_match(self::UUID_HEX, $hex, $matches)) {
            $message = sprintf('Invalid UUID hex: %s', $hex);
            throw DomainException::create($message);
        }

        $timeLow = $matches[1];
        $timeMid = $matches[2];
        $timeHiAndVersion = $matches[3];
        $clockSeqHiAndReserved = $matches[4];
        $clockSeqLow = $matches[5];
        $node = $matches[6];

        return new self($timeLow, $timeMid, $timeHiAndVersion, $clockSeqHiAndReserved, $clockSeqLow, $node);
    }

    /**
     * Creates instance from a byte string
     *
     * @param string $bytes The UUID bytes
     *
     * @return Uuid
     *
     * @throws DomainException When the bytes string is not valid
     */
    public static function fromBytes($bytes)
    {
        assert(Test::isString($bytes), sprintf(
            '%s expects $bytes to be a string; received (%s) %s',
            __METHOD__,
            gettype($bytes),
            VarPrinter::toString($bytes)
        ));

        if (strlen($bytes) !== 16) {
            $message = sprintf('%s expects $bytes to be a 16-byte string', __METHOD__);
            throw DomainException::create($message);
        }

        $steps = [];
        foreach (range(0, 15) as $step) {
            $steps[] = sprintf('%02x', ord($bytes[$step]));
            if (in_array($step, [3, 5, 7, 9])) {
                $steps[] = '-';
            }
        }

        return self::parse(implode('', $steps));
    }

    /**
     * Retrieves the timestamp
     *
     * The current time is defined as a 60-bit count of 100-nanosecond
     * intervals since 00:00:00.00, 15 October 1582.
     *
     * Return value is formatted as a hexadecimal string with time_low,
     * time_mid, and time_hi fields appearing in most significant bit order.
     *
     * @see http://tools.ietf.org/html/rfc4122#section-4.2.1
     *
     * @return string
     */
    public static function timestamp()
    {
        // 122192928000000000 is the number of 100-nanosecond intervals between
        // the UUID epoch 1582-10-15 00:00:00 and the Unix epoch 1970-01-01 00:00:00
        $offset = 122192928000000000;
        $timeofday = gettimeofday();

        $time = ($timeofday['sec'] * 10000000) + ($timeofday['usec'] * 10) + $offset;
        $hi = intval($time / 0xffffffff);

        $timestamp = [];
        $timestamp[] = sprintf('%04x', (($hi >> 16) & 0xfff));
        $timestamp[] = sprintf('%04x', $hi & 0xffff);
        $timestamp[] = sprintf('%08x', $time & 0xffffffff);

        return implode('', $timestamp);
    }

    /**
     * Checks if a UUID string matches the correct layout
     *
     * @param string $uuid The UUID string
     *
     * @return bool
     */
    public static function isValid($uuid)
    {
        if (!is_string($uuid)) {
            return false;
        }

        $uuid = strtolower(str_replace(['urn:', 'uuid:', '{', '}'], '', $uuid));

        if (!preg_match(self::UUID_PATTERN, $uuid)) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves the time_low field
     *
     * @return string
     */
    public function timeLow()
    {
        return $this->timeLow;
    }

    /**
     * Retrieves the time_mid field
     *
     * @return string
     */
    public function timeMid()
    {
        return $this->timeMid;
    }

    /**
     * Retrieves the time_hi_and_version field
     *
     * @return string
     */
    public function timeHiAndVersion()
    {
        return $this->timeHiAndVersion;
    }

    /**
     * Retrieves the clock_seq_hi_and_reserved field
     *
     * @return string
     */
    public function clockSeqHiAndReserved()
    {
        return $this->clockSeqHiAndReserved;
    }

    /**
     * Retrieves the clock_seq_low field
     *
     * @return string
     */
    public function clockSeqLow()
    {
        return $this->clockSeqLow;
    }

    /**
     * Retrieves the node field
     *
     * @return string
     */
    public function node()
    {
        return $this->node;
    }

    /**
     * Retrieves the most significant 64 bits in hexadecimal
     *
     * @return string
     */
    public function mostSignificantBits()
    {
        return sprintf('%s%s%s', $this->timeLow, $this->timeMid, $this->timeHiAndVersion);
    }

    /**
     * Retrieves the least significant 64 bits in hexadecimal
     *
     * @return string
     */
    public function leastSignificantBits()
    {
        return sprintf('%s%s%s', $this->clockSeqHiAndReserved, $this->clockSeqLow, $this->node);
    }

    /**
     * Retrieves the UUID version
     *
     * The version number is in the most significant 4 bits of the time stamp
     * as specified in section 4.1.3 of RFC 4122. If the version bits do not
     * match a known version, this method returns 0.
     *
     * The return value can be one of the following:
     *
     * * 1 - The time-based UUID version
     * * 2 - The DCE Security UUID version
     * * 3 - The name-based md5 hash UUID version
     * * 4 - The pseudo-randomly generated UUID version
     * * 5 - The name-based sha1 hash UUID version
     * * 0 - None of the above
     *
     * The Nil UUID (section 4.1.7) is an example of a UUID that does not match
     * a defined version number.
     *
     * @return int
     */
    public function version()
    {
        $version = hexdec(substr($this->timeHiAndVersion, 0, 1));

        switch ($version) {
            case self::VERSION_TIME:
                return self::VERSION_TIME;
                break;
            case self::VERSION_DCE:
                return self::VERSION_DCE;
                break;
            case self::VERSION_MD5:
                return self::VERSION_MD5;
                break;
            case self::VERSION_RANDOM:
                return self::VERSION_RANDOM;
                break;
            case self::VERSION_SHA1:
                return self::VERSION_SHA1;
                break;
            default:
                break;
        }

        return self::VERSION_UNKNOWN;
    }

    /**
     * Retrieves the UUID variant
     *
     * The variant field determines the layout of the UUID as specified in
     * section 4.1.1 of RFC 4122. The variant consists of a variable number
     * of the most significant bits of octet 8 of the UUID.
     *
     * The return value can be one of the following:
     *
     * * 0 - Reserved, NCS backward compatibility
     * * 2 - RFC 4122; supported by this class
     * * 6 - Reserved, Microsoft Corporation
     * * 7 - Reserved for future definition
     *
     * @return int
     */
    public function variant()
    {
        $octet = hexdec($this->clockSeqHiAndReserved);

        // test if the most significant bit is 0
        if (0b11111111 !== ($octet | 0b01111111)) {
            return self::VARIANT_RESERVED_NCS;
        }

        // test if the two most significant bits are 10
        if (0b10111111 === ($octet | 0b00111111)) {
            return self::VARIANT_RFC_4122;
        }

        // test if the three most significant bits are 110
        if (0b11011111 === ($octet | 0b00011111)) {
            return self::VARIANT_RESERVED_MICROSOFT;
        }

        // the most significant bits are 111
        return self::VARIANT_RESERVED_FUTURE;
    }

    /**
     * Retrieves a Uniform Resource Name (URN) representation
     *
     * @return string
     */
    public function toUrn()
    {
        return sprintf('urn:uuid:%s', $this->toString());
    }

    /**
     * Retrieves a hexidecimal string representation
     *
     * @return string
     */
    public function toHex()
    {
        return sprintf(
            '%s%s%s%s%s%s',
            $this->timeLow,
            $this->timeMid,
            $this->timeHiAndVersion,
            $this->clockSeqHiAndReserved,
            $this->clockSeqLow,
            $this->node
        );
    }

    /**
     * Retrieves a 16-byte string representation in network byte order
     *
     * @return string
     */
    public function toBytes()
    {
        $bytes = '';
        $hex = $this->toHex();

        foreach (range(0, 30, 2) as $step) {
            $bytes .= chr(hexdec(substr($hex, $step, 2)));
        }

        return $bytes;
    }

    /**
     * Retrieves an array representation
     *
     * @see http://tools.ietf.org/html/rfc4122#section-4.1.2
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'time_low'                  => $this->timeLow,
            'time_mid'                  => $this->timeMid,
            'time_hi_and_version'       => $this->timeHiAndVersion,
            'clock_seq_hi_and_reserved' => $this->clockSeqHiAndReserved,
            'clock_seq_low'             => $this->clockSeqLow,
            'node'                      => $this->node
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf(
            '%s-%s-%s-%s%s-%s',
            $this->timeLow,
            $this->timeMid,
            $this->timeHiAndVersion,
            $this->clockSeqHiAndReserved,
            $this->clockSeqLow,
            $this->node
        );
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

        $thisMsb = $this->mostSignificantBits();
        $thatMsb = $object->mostSignificantBits();

        if ($thisMsb > $thatMsb) {
            return 1;
        }
        if ($thisMsb < $thatMsb) {
            return -1;
        }

        $thisLsb = $this->leastSignificantBits();
        $thatLsb = $object->leastSignificantBits();

        if ($thisLsb > $thatLsb) {
            return 1;
        }
        if ($thisLsb < $thatLsb) {
            return -1;
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object)
    {
        if ($this === $object) {
            return true;
        }

        if (!Test::areSameType($this, $object)) {
            return false;
        }

        return $this->toHex() === $object->toHex();
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue()
    {
        return $this->toHex();
    }

    /**
     * Creates a UUID version 1
     *
     * Since this object does not have access to system state or other
     * persistence, the node and clock sequence may be passed from another
     * layer to this method following the rules of section 4.2.1.
     *
     * If node, clock sequence, or timestamp values are not passed in, they
     * will be generated.
     *
     * Generating the node and clock sequence are not recommended for this UUID
     * version. The current timestamp can be retrieved in the correct format
     * from the Uuid::timestamp() static method.
     *
     * @see http://tools.ietf.org/html/rfc4122#section-4.1.5
     * @see http://tools.ietf.org/html/rfc4122#section-4.1.6
     * @see http://tools.ietf.org/html/rfc4122#section-4.2.1
     *
     * @param string|null $node      The node ID or MAC address
     * @param int|null    $clockSeq  The clock sequence as a 14-bit integer
     * @param string|null $timestamp The timestamp
     *
     * @return Uuid
     *
     * @throws DomainException When clockSeq is not a 14-bit unsigned integer
     * @throws DomainException When the node is not 6 hexadecimal octets
     * @throws DomainException When timestamp is not 8 hexadecimal octets
     */
    private static function uuid1($node = null, $clockSeq = null, $timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = self::timestamp();
        }
        $timestamp = strtolower((string) $timestamp);
        self::guardTimestamp($timestamp);

        // format time fields with version
        $timeLow = substr($timestamp, 8, 8);
        $timeMid = substr($timestamp, 4, 4);
        $timeHi = substr($timestamp, 0, 4);
        $timeHiAndVersion = hexdec($timeHi);
        $timeHiAndVersion |= (self::VERSION_TIME << 12);
        $timeHiAndVersion = sprintf('%04x', $timeHiAndVersion);

        if ($clockSeq === null) {
            $clockSeq = mt_rand(0, 1 << 14);
        }
        $clockSeq = (int) $clockSeq;
        self::guardClockSeq($clockSeq);

        // format clock sequence with variant
        $clockSeqLow = sprintf('%02x', $clockSeq & 0xff);
        $clockSeqHiAndReserved = ($clockSeq & 0x3f00) >> 8;
        $clockSeqHiAndReserved |= 0x80;
        $clockSeqHiAndReserved = sprintf('%02x', $clockSeqHiAndReserved);

        if ($node === null) {
            $node = bin2hex(random_bytes(6));
        } else {
            // remove formatting from MAC address if present
            $node = str_replace([':', '-', '.'], '', (string) $node);
        }
        $node = strtolower($node);
        self::guardNode($node);

        return new self($timeLow, $timeMid, $timeHiAndVersion, $clockSeqHiAndReserved, $clockSeqLow, $node);
    }

    /**
     * Creates a UUID version 3
     *
     * @param Uuid|string $namespace A valid UUID namespace
     * @param string      $name      A unique string within the namespace
     *
     * @return Uuid
     *
     * @throws DomainException When the namespace is not a valid UUID
     */
    private static function uuid3($namespace, $name)
    {
        $name = (string) $name;

        if (!($namespace instanceof self)) {
            $namespace = self::parse($namespace);
        }

        $hash = md5($namespace->toBytes().$name);

        return self::fromUnformatted($hash, self::VERSION_MD5);
    }

    /**
     * Creates a UUID version 4
     *
     * @return Uuid
     */
    private static function uuid4()
    {
        $hex = bin2hex(random_bytes(16));

        return self::fromUnformatted($hex, self::VERSION_RANDOM);
    }

    /**
     * Creates a UUID version 5
     *
     * @param Uuid|string $namespace A valid UUID namespace
     * @param string      $name      A unique string within the namespace
     *
     * @return Uuid
     *
     * @throws DomainException When the namespace is not a valid UUID
     */
    private static function uuid5($namespace, $name)
    {
        $name = (string) $name;

        if (!($namespace instanceof self)) {
            $namespace = self::parse($namespace);
        }

        $hash = sha1($namespace->toBytes().$name);

        return self::fromUnformatted($hash, self::VERSION_SHA1);
    }

    /**
     * Creates a formatted UUID from a hexadecimal string
     *
     * This method multiplexes the version and variant bits as described in
     * sections 4.3 and 4.4 of RFC 4122. Supports versions 3, 4, and 5 UUIDs.
     *
     * @see http://tools.ietf.org/html/rfc4122#section-4.3
     * @see http://tools.ietf.org/html/rfc4122#section-4.4
     *
     * @param string $hex     A 32 character hexadecimal string
     * @param int    $version A version from section 4.1.3 of RFC 4122
     *
     * @return Uuid
     */
    private static function fromUnformatted($hex, $version)
    {
        $timeLow = substr($hex, 0, 8);
        $timeMid = substr($hex, 8, 4);
        $timeHi = substr($hex, 12, 4);
        $clockSeqHi = substr($hex, 16, 2);
        $clockSeqLow = substr($hex, 18, 2);
        $node = substr($hex, 20, 12);

        // version
        $timeHiAndVersion = hexdec($timeHi);
        $timeHiAndVersion &= 0x0fff;
        $timeHiAndVersion |= ($version << 12);
        $timeHiAndVersion = sprintf('%04x', $timeHiAndVersion);

        // variant
        $clockSeqHiAndReserved = hexdec($clockSeqHi);
        $clockSeqHiAndReserved &= 0x3f;
        $clockSeqHiAndReserved |= 0x80;
        $clockSeqHiAndReserved = sprintf('%02x', $clockSeqHiAndReserved);

        return new self($timeLow, $timeMid, $timeHiAndVersion, $clockSeqHiAndReserved, $clockSeqLow, $node);
    }

    /**
     * Validates a timestamp hexadecimal string
     *
     * @param string $timestamp The timestamp
     *
     * @return void
     *
     * @throws DomainException When timestamp is not 8 hexadecimal octets
     */
    private static function guardTimestamp($timestamp)
    {
        if (!preg_match('/\A[a-f0-9]{16}\z/', $timestamp)) {
            $message = 'Timestamp must be a 16 character hexadecimal string';
            throw DomainException::create($message);
        }
    }

    /**
     * Validates a clock sequence integer
     *
     * @param int $clockSeq The clock sequence
     *
     * @return void
     *
     * @throws DomainException When clockSeq is not a 14-bit unsigned integer
     */
    private static function guardClockSeq($clockSeq)
    {
        if ($clockSeq < 0b0 || $clockSeq > 0b11111111111111) {
            $message = 'Clock sequence must be a 14-bit unsigned integer value';
            throw DomainException::create($message);
        }
    }

    /**
     * Validates a node hexadecimal string
     *
     * @param string $node The node
     *
     * @return void
     *
     * @throws DomainException When the node is not 6 hexadecimal octets
     */
    private static function guardNode($node)
    {
        if (!preg_match('/\A[a-f0-9]{12}\z/', $node)) {
            $message = 'Node must be a 12 character hexadecimal string';
            throw DomainException::create($message);
        }
    }
}
