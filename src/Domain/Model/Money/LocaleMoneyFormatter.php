<?php

namespace Novuso\Common\Domain\Model\Money;

use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;
use NumberFormatter;

/**
 * LocaleMoneyFormatter is a locale-aware money formatter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class LocaleMoneyFormatter implements MoneyFormatter
{
    /**
     * Formatter
     *
     * @var NumberFormatter
     */
    private $formatter;

    /**
     * Constructs LocaleMoneyFormatter
     *
     * @internal
     *
     * @param NumberFormatter $formatter The number formatter
     */
    private function __construct(NumberFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Creates instance from a locale string
     *
     * @param string $locale The locale string
     *
     * @return LocaleMoneyFormatter
     */
    public static function fromLocale($locale)
    {
        assert(Test::isString($locale), sprintf(
            '%s expects $locale to be a string; received (%s) %s',
            __METHOD__,
            gettype($locale),
            VarPrinter::toString($locale)
        ));

        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return new self($formatter);
    }

    /**
     * {@inheritdoc}
     */
    public function format(Money $money)
    {
        $amount = $money->amount();
        $minor = $money->currency()->minor();
        $digits = $money->currency()->digits();
        $code = $money->currency()->code();
        $float = round($amount / $minor, $digits);

        return $this->formatter->formatCurrency($float, $code);
    }
}
