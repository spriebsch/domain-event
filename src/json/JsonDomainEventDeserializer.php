<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

final readonly class JsonDomainEventDeserializer implements DomainEventDeserializer
{
    /** @param class-string<DomainEvent> $class */
    public function deserialize(string $domainEvent, string $class): DomainEvent
    {
        if (!is_a($class, DomainEvent::class, true)) {
            throw new \InvalidArgumentException('Class must implement DomainEvent');
        }

        return $class::fromJson($domainEvent);
    }
}
