<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

trait JsonDomainEventTrait
{
    /** @return array<string, mixed> */
    abstract public function asArray(): array;

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return $this->asArray();
    }
}
