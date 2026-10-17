<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

trait EmptyJsonEventTrait
{
    use JsonDomainEventTrait;

    public function asArray(): array
    {
        return [];
    }

    public static function fromJson(string $json): static
    {
        if (json_decode($json, true, 512, JSON_THROW_ON_ERROR) !== []) {
            throw new \InvalidArgumentException('Expected an empty event payload');
        }

        return new static();
    }
}
