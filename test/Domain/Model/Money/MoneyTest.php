<?php

namespace Novuso\Test\Common\Domain\Model\Money;

use Novuso\Common\Domain\Model\Money\Money;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\Money\Money
 */
class MoneyTest extends PHPUnit_Framework_TestCase
{
    public function test_that_magic_constructor_returns_expected_instance()
    {
        $money = Money::USD(1725);
        $this->assertSame('$17.25', $money->format());
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $string = 'USD:1725';
        $money = Money::fromString($string);
        $this->assertSame($string, $money->toString());
    }

    public function test_that_from_string_returns_expected_negative()
    {
        $string = 'USD:-1725';
        $money = Money::fromString($string);
        $this->assertSame($string, $money->toString());
    }

    public function test_that_is_zero_returns_true_when_amount_is_zero()
    {
        $money = Money::EUR(0);
        $this->assertTrue($money->isZero());
    }

    public function test_that_is_zero_returns_false_when_amount_is_not_zero()
    {
        $money = Money::EUR(-1000);
        $this->assertFalse($money->isZero());
    }

    public function test_that_is_positive_returns_true_when_amount_is_positive()
    {
        $money = Money::EUR(1000);
        $this->assertTrue($money->isPositive());
    }

    public function test_that_is_positive_returns_false_when_amount_is_negative()
    {
        $money = Money::EUR(-1000);
        $this->assertFalse($money->isPositive());
    }

    public function test_that_is_negative_returns_true_when_amount_is_negative()
    {
        $money = Money::EUR(-1000);
        $this->assertTrue($money->isNegative());
    }

    public function test_that_is_negative_returns_false_when_amount_is_positive()
    {
        $money = Money::EUR(1000);
        $this->assertFalse($money->isNegative());
    }

    public function test_that_with_amount_returns_expected_instance()
    {
        $money = Money::USD(100);
        $money = $money->withAmount(1500);
        $this->assertSame('USD:1500', $money->toString());
    }

    public function test_that_add_returns_expected_instance()
    {
        $balance = Money::USD(45603);
        $credit = Money::USD(1434078);
        $balance = $balance->add($credit);
        $this->assertSame('$14,796.81', $balance->format());
    }

    public function test_that_subtract_returns_expected_instance()
    {
        $balance = Money::USD(45603);
        $debit = Money::USD(11500);
        $balance = $balance->subtract($debit);
        $this->assertSame('$341.03', $balance->format());
    }

    public function test_that_multiply_returns_expected_instance()
    {
        $money = Money::EUR(1535);
        $money = $money->multiply(5);
        $this->assertSame('€76.75', $money->format());
    }

    public function test_that_divide_returns_expected_instance()
    {
        $money = Money::EUR(1535);
        $money = $money->divide(5);
        $this->assertSame('€3.07', $money->format());
    }

    public function test_that_allocate_correctly_allocates_money()
    {
        $money = Money::USD(326501);
        $ratios = [0.1, 0.3, 0.15, 0.15, 0.15, 0.15];
        $shares = $money->allocate($ratios);
        $output = [];
        foreach ($shares as $share) {
            $output[] = $share->format();
        }
        $expected = '[$326.51, $979.50, $489.75, $489.75, $489.75, $489.75]';
        $this->assertSame($expected, sprintf('[%s]', implode(', ', $output)));
    }

    public function test_that_split_evenly_divides_money()
    {
        $money = Money::USD(326501);
        $shares = $money->split(4);
        $output = [];
        foreach ($shares as $share) {
            $output[] = $share->format();
        }
        $expected = '[$816.26, $816.25, $816.25, $816.25]';
        $this->assertSame($expected, sprintf('[%s]', implode(', ', $output)));
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $money = Money::USD(1725);
        $this->assertSame(0, $money->compareTo($money));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $money1 = Money::USD(1725);
        $money2 = Money::USD(1725);
        $this->assertSame(0, $money1->compareTo($money2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        $money1 = Money::USD(1726);
        $money2 = Money::USD(1725);
        $this->assertSame(1, $money1->compareTo($money2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $money1 = Money::USD(1725);
        $money2 = Money::USD(1726);
        $this->assertSame(-1, $money1->compareTo($money2));
    }

    public function test_that_greater_than_returns_true_for_greater_value()
    {
        $money1 = Money::USD(1726);
        $money2 = Money::USD(1725);
        $this->assertTrue($money1->greaterThan($money2));
    }

    public function test_that_greater_than_returns_false_for_lesser_value()
    {
        $money1 = Money::USD(1725);
        $money2 = Money::USD(1726);
        $this->assertFalse($money1->greaterThan($money2));
    }

    public function test_that_greater_than_or_equal_returns_true_for_greater_value()
    {
        $money1 = Money::USD(1726);
        $money2 = Money::USD(1725);
        $this->assertTrue($money1->greaterThanOrEqual($money2));
    }

    public function test_that_greater_than_or_equal_returns_true_for_equal_value()
    {
        $money1 = Money::USD(1725);
        $money2 = Money::USD(1725);
        $this->assertTrue($money1->greaterThanOrEqual($money2));
    }

    public function test_that_greater_than_or_equal_returns_false_for_lesser_value()
    {
        $money1 = Money::USD(1725);
        $money2 = Money::USD(1726);
        $this->assertFalse($money1->greaterThanOrEqual($money2));
    }

    public function test_that_less_than_returns_true_for_lesser_value()
    {
        $money1 = Money::USD(1725);
        $money2 = Money::USD(1726);
        $this->assertTrue($money1->lessThan($money2));
    }

    public function test_that_less_than_returns_false_for_greater_value()
    {
        $money1 = Money::USD(1726);
        $money2 = Money::USD(1725);
        $this->assertFalse($money1->lessThan($money2));
    }

    public function test_that_less_than_or_equal_returns_true_for_lesser_value()
    {
        $money1 = Money::USD(1725);
        $money2 = Money::USD(1726);
        $this->assertTrue($money1->lessThanOrEqual($money2));
    }

    public function test_that_less_than_or_equal_returns_true_for_equal_value()
    {
        $money1 = Money::USD(1725);
        $money2 = Money::USD(1725);
        $this->assertTrue($money1->lessThanOrEqual($money2));
    }

    public function test_that_less_than_or_equal_returns_false_for_greater_value()
    {
        $money1 = Money::USD(1726);
        $money2 = Money::USD(1725);
        $this->assertFalse($money1->lessThanOrEqual($money2));
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_magic_constructor_throws_exception_for_invalid_type()
    {
        Money::USD('1725');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_from_string_throws_exception_for_invalid_formatting()
    {
        Money::fromString('$17.25');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_divide_throws_exception_for_division_by_zero()
    {
        $money = Money::USD(100);
        $money->divide(0);
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_multiply_throws_exception_for_invalid_type()
    {
        $money = Money::USD(100);
        $money->multiply('foo');
    }

    /**
     * @expectedException Novuso\System\Exception\RangeException
     */
    public function test_that_multiply_throws_exception_for_result_out_of_bounds()
    {
        $money = Money::USD(PHP_INT_MAX);
        $money->multiply(1.5);
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_compare_to_throws_exception_for_different_currency()
    {
        $money1 = Money::USD(1725);
        $money2 = Money::EUR(1725);
        $money1->compareTo($money2);
    }
}
