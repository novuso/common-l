<?php
/**
 * This file is part of the Novuso Framework
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */

if (!function_exists('random_bytes')) {
    /**
     * Generates random bytes
     *
     * @param int $length The number of bytes
     *
     * @return string
     *
     * @throws Exception When length is invalid
     * @throws Exception When unable to generate random data
     */
    function random_bytes($length)
    {
        if (!is_int($length)) {
            throw new Exception('Length must be an integer');
        }

        if ($length < 1) {
            throw new Exception('Length must be greater than 0');
        }

        if (function_exists('mcrypt_create_iv') && version_compare(PHP_VERSION, '5.3.7') >= 0) {
            $buffer = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if ($buffer !== false) {
                if (novuso_binary_strlen($buffer) === $length) {
                    return $buffer;
                }
            }
        }

        if (!ini_get('open_basedir') && (is_readable('/dev/arandom') || is_readable('/dev/urandom'))) {
            if (is_readable('/dev/arandom')) {
                $fp = fopen('/dev/arandom', 'r');
            } else {
                $fp = fopen('/dev/urandom', 'r');
            }
            $buffer = '';
            if ($fp !== false) {
                $bytes = 0;
                while ($bytes < $length) {
                    $read = fread($fp, $length - $bytes);
                    if ($read === false) {
                        $buffer = false;
                        break;
                    }
                    $buffer .= $read;
                    $bytes = novuso_binary_strlen($buffer);
                }
                fclose($fp);
            }
            if ($buffer !== false) {
                if (novuso_binary_strlen($buffer) === $length) {
                    return $buffer;
                }
            }
        }

        if (extension_loaded('com_dotnet')) {
            $buffer = '';
            $util = new COM('CAPICOM.Utilities.1');
            $count = 0;
            while ($count < $length) {
                $buffer .= base64_decode($util->GetRandom($length, 0));
                if (novuso_binary_strlen($buffer) >= $length) {
                    return novuso_binary_substr($buffer, 0, $length);
                }
                $count++;
            }
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            $strong = true;
            $buffer = openssl_random_pseudo_bytes($length, $strong);
            if ($buffer !== false && $strong) {
                if (novuso_binary_strlen($buffer) === $length) {
                    return $buffer;
                }
            }
        }

        throw new Exception('Unable to generate secure random data');
    }
}

if (!function_exists('random_int')) {
    /**
     * Generates a random integer
     *
     * @param int $min The lower bound
     * @param int $max The upper bound
     *
     * @return int
     *
     * @throws Exception When either input is invalid
     * @throws Exception When unable to generate random data
     */
    function random_int($min, $max)
    {
        if (!is_int($min)) {
            throw new Exception('Min must be an integer');
        }

        if (!is_int($max)) {
            throw new Exception('Max must be an integer');
        }

        if ($min > $max) {
            throw new Exception('Min must be less than or equal to the max');
        }

        if ($max === $min) {
            return $min;
        }

        $range = $max - $min;

        if ($range < 1 || $range > PHP_INT_MAX) {
            throw new Exception(sprintf('Invalid range for random int: %s', (string) $range));
        }

        $bitcount = function ($number) {
            $log2 = 0;
            while ($number >>= 1) {
                $log2++;
            }

            return $log2;
        };

        $bits = $bitcount($range) + 1;
        $bytes = (int) max(ceil($bits / 8), 1);

        if ($bits === 63) {
            $mask = 0x7fffffffffffffff;
        } else {
            $mask = (int) (pow(2, $bits) - 1);
        }

        do {
            $test = random_bytes($bytes);
            $result = hexdec(bin2hex($test)) & $mask;
        } while ($result > $range);

        return $result + $min;
    }
}

/**
 * Retrieves the binary string length
 *
 * Safe to use when mbstring.func_overload is in effect.
 *
 * @param string $string The input string
 *
 * @return int
 */
function novuso_binary_strlen($string)
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($string, '8bit');
    }

    return strlen($string);
}

/**
 * Retrieves a binary substring
 *
 * Safe to use when mbstring.func_overload is in effect.
 *
 * @param string $string The input string
 * @param int    $start  The starting offset
 * @param int    $length The length in bytes
 *
 * @return string
 */
function novuso_binary_substr($string, $start, $length)
{
    if (function_exists('mb_substr')) {
        return mb_substr($string, $start, $length, '8bit');
    }

    return substr($string, $start, $length);
}
