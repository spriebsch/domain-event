<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use Crell\Serde\Attributes\StaticTypeMap;

#[StaticTypeMap(
    key: 'type',
    map: [
        'interface'       => ImplementsInterface::class,
        'secondInterface' => SecondInterfaceImplementation::class,
    ],
)]
interface SomeInterface {}
