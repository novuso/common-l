<?php

namespace Novuso\Common\Domain\Model\Money;

use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\RangeException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * Money represents a monetary value
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class Money extends ValueObject implements Comparable
{
    /**
     * Monetary amount
     *
     * Uses the currency sub-unit.
     *
     * @var int
     */
    protected $amount;

    /**
     * Currency
     *
     * @var Currency
     */
    protected $currency;

    /**
     * Constructs Money
     *
     * @internal
     *
     * @param int      $amount   The monetary amount
     * @param Currency $currency The currency
     */
    private function __construct($amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Creates instance from a currency name and amount
     *
     * Maps static method `Money::USD(1725)` where `USD` is a class constant of
     * `Currency` and `1725` is the number of minor-units (eg. cents) in the
     * given curreny. The monetary value for this example would be $17.25 USD.
     *
     * @param string $method The name of the method
     * @param array  $args   A list of arguments
     *
     * @return Money
     *
     * @throws DomainException When the currency code is invalid
     * @throws TypeException When the first argument is not an integer
     */
    public static function __callStatic($method, array $args)
    {
        if (!isset($args[0]) || !is_int($args[0])) {
            $message = sprintf(
                '%s expects an integer amount, expressed in the smallest unit of currency',
                __METHOD__
            );
            throw TypeException::create($message);
        }

        $amount = $args[0];
        $currency = Currency::fromValue($method);

        return new self($amount, $currency);
    }

    /**
     * Creates instance from string representation
     *
     * The expected format matches the format returned by toString(). The
     * currency code followed by the integer amount.
     *
     * Example: $17.25 USD would be represented as: "USD:1725"
     *
     * @param string $money The string representation
     *
     * @return Money
     *
     * @throws DomainException When the format or value is invalid
     */
    public static function fromString($money)
    {
        $pattern = '/\A(?P<code>[A-Z]{3}):(?P<amount>-?[\d]+)\z/';

        if (!preg_match($pattern, (string) $money, $matches)) {
            $message = sprintf('Format must include currency and amount (eg. USD:1725); received "%s"', $money);
            throw DomainException::create($message);
        }

        $amount = (int) $matches['amount'];
        $currency = Currency::fromValue($matches['code']);

        return new self($amount, $currency);
    }

    /**
     * Checks if the amount is zero
     *
     * @return bool
     */
    public function isZero()
    {
        return $this->amount === 0;
    }

    /**
     * Checks if the amount is positive
     *
     * @return bool
     */
    public function isPositive()
    {
        return $this->amount > 0;
    }

    /**
     * Checks if the amount is negative
     *
     * @return bool
     */
    public function isNegative()
    {
        return $this->amount < 0;
    }

    /**
     * Retrieves the amount
     *
     * @return int
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * Retrieves the currency
     *
     * @return Currency
     */
    public function currency()
    {
        return $this->currency;
    }

    /**
     * Creates instance with a given amount using the same currency
     *
     * @param int $amount The amount
     *
     * @return Money
     *
     * @throws TypeException When amount is not an integer
     */
    public function withAmount($amount)
    {
        if (!is_int($amount)) {
            $message = sprintf(
                '%s expects $amount to be an integer; received (%s) %s',
                __METHOD__,
                gettype($amount),
                VarPrinter::toString($amount)
            );
            throw TypeException::create($message);
        }

        return new self($amount, $this->currency);
    }

    /**
     * Creates instance that adds the given monetary value
     *
     * @param Money $other The other monetary value
     *
     * @return Money
     *
     * @throws DomainException When the other money uses a different currency
     * @throws RangeException When integer overflow occurs
     */
    public function add(Money $other)
    {
        $this->guardCurrency($other);

        $amount = $this->amount + $other->amount;

        $this->guardAmountInBounds($amount);

        return $this->withAmount($amount);
    }

    /**
     * Creates instance that subtracts the given monetary value
     *
     * @param Money $other The other monetary value
     *
     * @return Money
     *
     * @throws DomainException When the other money uses a different currency
     * @throws RangeException When integer overflow occurs
     */
    public function subtract(Money $other)
    {
        $this->guardCurrency($other);

        $amount = $this->amount - $other->amount;

        $this->guardAmountInBounds($amount);

        return $this->withAmount($amount);
    }

    /**
     * Creates instance that multiplies this value by the given value
     *
     * @param int|float         $multiplier The multiplier
     * @param RoundingMode|null $mode       The rounding mode; null for HALF_UP
     *
     * @return Money
     *
     * @throws TypeException When the multiplier is not an integer or float
     * @throws RangeException When integer overflow occurs
     */
    public function multiply($multiplier, RoundingMode $mode = null)
    {
        if ($mode === null) {
            $mode = RoundingMode::HALF_UP();
        }

        $this->guardOperand($multiplier);

        $amount = round($this->amount * $multiplier, 0, $mode->value());
        $amount = $this->castToInteger($amount);

        return $this->withAmount($amount);
    }

    /**
     * Creates instance that divides this value by the given value
     *
     * @param int|float         $divisor The divisor
     * @param RoundingMode|null $mode    The rounding mode; null for HALF_UP
     *
     * @return Money
     *
     * @throws TypeException When the divisor is not an integer or float
     * @throws DomainException When the divisor is zero
     * @throws RangeException When integer overflow occurs
     */
    public function divide($divisor, RoundingMode $mode = null)
    {
        if ($mode === null) {
            $mode = RoundingMode::HALF_UP();
        }

        $this->guardOperand($divisor);

        if ($divisor === 0 || $divisor === 0.0) {
            throw DomainException::create('Division by zero');
        }

        $amount = round($this->amount / $divisor, 0, $mode->value());
        $amount = $this->castToInteger($amount);

        return $this->withAmount($amount);
    }

    /**
     * Allocates the money according to a list of ratios
     *
     * @param array $ratios The list of ratios
     *
     * @return Money[]
     */
    public function allocate(array $ratios)
    {
        $shares = [];
        $total = array_sum($ratios);
        $remainder = $this->amount;

        foreach ($ratios as $ratio) {
            $amount = $this->castToInteger($this->amount * $ratio / $total);
            $shares[] = $this->withAmount($amount);
            $remainder -= $amount;
        }

        for ($i = 0; $i < $remainder; $i++) {
            $shares[$i] = $this->withAmount($shares[$i]->amount + 1);
        }

        return $shares;
    }

    /**
     * Allocates the money among a given number of targets
     *
     * @param int $num The number of targets
     *
     * @return Money[]
     */
    public function split($num)
    {
        assert(Test::naturalNumber($num), sprintf('%s expects $num to be greater than zero', __METHOD__));
        $num = (int) $num;

        $shares = [];
        $amount = $this->castToInteger($this->amount / $num);
        $remainder = $this->amount % $num;

        for ($i = 0; $i < $num; $i++) {
            $shares[] = $this->withAmount($amount);
        }

        for ($i = 0; $i < $remainder; $i++) {
            $shares[$i] = $this->withAmount($shares[$i]->amount + 1);
        }

        return $shares;
    }

    /**
     * Retrieves a formatted string representation
     *
     * @param string $locale The locale
     *
     * @return string
     */
    public function format($locale = 'en_US')
    {
        return LocaleFormatter::fromLocale((string) $locale)->format($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:%d', $this->currency->code(), $this->amount);
    }

    /**
     * Checks whether other money has the same currency
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function isSameCurrency(Money $other)
    {
        return $this->currency->equals($other->currency);
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

        $this->guardCurrency($object);

        $thisAmt = $this->amount;
        $thatAmt = $object->amount;

        if ($thisAmt > $thatAmt) {
            return 1;
        }
        if ($thisAmt < $thatAmt) {
            return -1;
        }

        return 0;
    }

    /**
     * Checks if this monetary value is greater than another
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function greaterThan(Money $other)
    {
        return $this->compareTo($other) === 1;
    }

    /**
     * Checks if this monetary value is greater or equal to another
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function greaterThanOrEqual(Money $other)
    {
        return $this->compareTo($other) >= 0;
    }

    /**
     * Checks if this monetary value is less than another
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function lessThan(Money $other)
    {
        return $this->compareTo($other) === -1;
    }

    /**
     * Checks if this monetary value is less or equal to another
     *
     * @param Money $other The other monetary value
     *
     * @return bool
     */
    public function lessThanOrEqual(Money $other)
    {
        return $this->compareTo($other) <= 0;
    }

    /**
     * Casts amount to an integer after math operation; checking boundaries
     *
     * @param mixed $amount The amount
     *
     * @return int
     *
     * @throws RangeException When integer overflow occurs
     */
    private function castToInteger($amount)
    {
        $this->guardAmountInBounds($amount);

        return (int) $amount;
    }

    /**
     * Validates amount did not overflow integer bounds
     *
     * @param mixed $amount The amount
     *
     * @return void
     *
     * @throws RangeException When integer overflow occurs
     */
    private function guardAmountInBounds($amount)
    {
        if (abs($amount) > PHP_INT_MAX) {
            throw RangeException::create('Integer overflow');
        }
    }

    /**
     * Validates monetary operand is an integer or float
     *
     * @param mixed $operand The operand
     *
     * @return void
     *
     * @throws TypeException When the operand is not an integer or float
     */
    private function guardOperand($operand)
    {
        if (!is_int($operand) && !is_float($operand)) {
            $message = sprintf(
                'Operand must be an integer or float; received (%s) %s',
                gettype($operand),
                VarPrinter::toString($operand)
            );
            throw TypeException::create($message);
        }
    }

    /**
     * Validates another monetary value uses the same currency
     *
     * @param Money $other The other monetary value
     *
     * @return void
     *
     * @throws DomainException When the other money uses a different currency
     */
    private function guardCurrency(Money $other)
    {
        if (!$this->isSameCurrency($other)) {
            throw DomainException::create('Math and comparison operations require the same currency');
        }
    }
}
