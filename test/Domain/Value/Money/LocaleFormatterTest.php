<?php

namespace Novuso\Test\Common\Domain\Value\Money;

use Novuso\Common\Domain\Value\Money\LocaleFormatter;
use Novuso\Common\Domain\Value\Money\Money;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Value\Money\LocaleFormatter
 */
class LocaleFormatterTest extends PHPUnit_Framework_TestCase
{
    public function test_that_format_returns_expected_output_for_locale()
    {
        $formatter = LocaleFormatter::fromLocale('en_US');
        $this->assertSame('$1,100.75', $formatter->format(Money::USD(110075)));
    }
}
