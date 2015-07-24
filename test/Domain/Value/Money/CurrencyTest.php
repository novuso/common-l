<?php

namespace Novuso\Test\Common\Domain\Value\Money;

use Novuso\Common\Domain\Value\Money\Currency;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Value\Money\Currency
 */
class CurrencyTest extends PHPUnit_Framework_TestCase
{
    public function test_that_display_name_returns_expected_value()
    {
        $currency = Currency::USD();
        $this->assertSame('US Dollar', $currency->displayName());
    }

    public function test_that_code_returns_expected_value()
    {
        $currency = Currency::GBP();
        $this->assertSame('GBP', $currency->code());
    }

    public function test_that_numeric_code_returns_expected_value()
    {
        $currency = Currency::EUR();
        $this->assertSame(978, $currency->numericCode());
    }

    public function test_that_digits_returns_expected_value()
    {
        $currency = Currency::JPY();
        $this->assertSame(0, $currency->digits());
    }

    public function test_that_minor_returns_expected_value()
    {
        $currency = Currency::IQD();
        $this->assertSame(1000, $currency->minor());
    }
}
