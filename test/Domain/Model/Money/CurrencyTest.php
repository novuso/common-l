<?php

namespace Novuso\Test\Common\Domain\Model\Money;

use Novuso\Common\Domain\Model\Money\Currency;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\Money\Currency
 */
class CurrencyTest extends PHPUnit_Framework_TestCase
{
    public function test_that_display_name_returns_expected_value()
    {
        $currency = Currency::USD();
        $this->assertSame('US Dollar', $currency->displayName());
    }
}
