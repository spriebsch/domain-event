<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

final readonly class ImplementsInterface implements SomeInterface
{
    public function __construct(private string $value) {}

    public function value(): string
    {
        return $this->value;
    }
}
