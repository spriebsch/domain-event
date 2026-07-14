<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use Crell\Serde\Attributes\StaticTypeMap;

#[StaticTypeMap(
    key: 'type',
    map: [
        ImplementsInterface::class => ImplementsInterface::class,
        SecondInterfaceImplementation::class => SecondInterfaceImplementation::class,
    ],
)]
interface SomeInterface
{
}
