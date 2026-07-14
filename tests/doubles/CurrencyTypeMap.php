<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use Crell\Serde\TypeMap;

final class CurrencyTypeMap implements TypeMap
{
    public function keyField(): string
    {
        return 'type';
    }

    public function findClass(string $id): ?string
    {
        return match ($id) {
            TestSupportedCurrencies::class => TestSupportedCurrencies::class,
            default                        => null,
        };
    }

    public function findIdentifier(string $class): ?string
    {
        return match ($class) {
            TestSupportedCurrencies::class => TestSupportedCurrencies::class,
            default                        => null,
        };
    }
}
