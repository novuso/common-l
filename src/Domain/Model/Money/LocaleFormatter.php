<?php

namespace Novuso\Common\Domain\Model\Money;

use NumberFormatter;

/**
 * LocaleFormatter is a locale-aware money formatter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class LocaleFormatter implements Formatter
{
    /**
     * Formatter
     *
     * @var NumberFormatter
     */
    protected $formatter;

    /**
     * Constructs LocaleFormatter
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
     * @return LocaleFormatter
     */
    public static function fromLocale($locale)
    {
        $formatter = new NumberFormatter((string) $locale, NumberFormatter::CURRENCY);

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
