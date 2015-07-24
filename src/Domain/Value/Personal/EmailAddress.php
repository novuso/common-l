<?php

namespace Novuso\Common\Domain\Value\Personal;

use Novuso\Common\Domain\Value\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * EmailAddress represents an email address
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EmailAddress extends ValueObject implements Comparable
{
    /**
     * Email address value
     *
     * @var string
     */
    protected $email;

    /**
     * Constructs EmailAddress
     *
     * @internal
     *
     * @param string $email The email address value
     *
     * @throws TypeException When email is not a string
     * @throws DomainException When the email address is not valid
     */
    private function __construct($email)
    {
        if (!is_string($email)) {
            $message = sprintf(
                '%s expects $email to be a string; received (%s) %s',
                __METHOD__,
                gettype($email),
                VarPrinter::toString($email)
            );
            throw TypeException::create($message);
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $message = sprintf(
                'Invalid email address: %s',
                VarPrinter::toString($email)
            );
            throw DomainException::create($message);
        }

        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString($state)
    {
        return new self($state);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return $this->email;
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

        $comp = strnatcmp($this->email, $object->email);

        if ($comp > 0) {
            return 1;
        }
        if ($comp < 0) {
            return -1;
        }

        return 0;
    }
}
