<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;


use spriebsch\money\Currency;

enum TestSupportedCurrencies: string implements Currency
{
    case GBP = 'GBP';
    case EUR = 'EUR';
}
