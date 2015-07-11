<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model\Contact;

use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\{Test, VarPrinter};

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
    protected $value;

    /**
     * Constructs EmailAddress
     *
     * @internal
     *
     * @param string $value The email address value
     *
     * @throws DomainException When the email address is not valid
     */
    private function __construct(string $value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $message = sprintf(
                'Invalid email address: %s',
                VarPrinter::toString($value)
            );
            throw DomainException::create($message);
        }

        $this->value = $value;
    }

    /**
     * Creates instance from a string
     *
     * @param string $value The email address string
     *
     * @return EmailAddress
     */
    public static function fromString(string $value): EmailAddress
    {
        return new self($value);
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): string
    {
        return $this->value;
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

        $comp = strnatcmp($this->value(), $object->value());

        if ($comp > 0) {
            return 1;
        }
        if ($comp < 0) {
            return -1;
        }

        return 0;
    }
}
