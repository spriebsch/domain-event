<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

final readonly class SecondInterfaceImplementation implements SomeInterface
{
    public function __construct(private int $value) {}

    public function value(): int
    {
        return $this->value;
    }
}
