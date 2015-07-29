<?php

namespace Novuso\Common\Domain\Value\Identifier;

use Novuso\Common\Domain\Value\ValueObject;
use Novuso\System\Exception\DomainException;
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
     * @throws DomainException When the email address is not valid
     */
    private function __construct($email)
    {
        assert(Test::isString($email), sprintf(
            '%s expects $email to be a string; received (%s) %s',
            __METHOD__,
            gettype($email),
            VarPrinter::toString($email)
        ));

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
     * Creates instance from an email string
     *
     * @param string $email The email string
     *
     * @return EmailAddress
     *
     * @throws DomainException When the string is invalid
     */
    public static function fromString($email)
    {
        return new self($email);
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
