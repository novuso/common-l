<?php

namespace Novuso\Test\Common\Domain\Model\Money;

use Novuso\Common\Domain\Model\Money\LocaleMoneyFormatter;
use Novuso\Common\Domain\Model\Money\Money;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\Money\LocaleMoneyFormatter
 */
class LocaleMoneyFormatterTest extends PHPUnit_Framework_TestCase
{
    public function test_that_format_returns_expected_output_for_locale()
    {
        $formatter = LocaleMoneyFormatter::fromLocale('en_US');
        $this->assertSame('$1,100.75', $formatter->format(Money::USD(110075)));
    }
}
