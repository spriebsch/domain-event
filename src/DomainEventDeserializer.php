<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

interface DomainEventDeserializer
{
    /** @param class-string<DomainEvent> $class */
    public function deserialize(string $domainEvent, string $class): DomainEvent;
}
