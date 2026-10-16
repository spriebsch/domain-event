<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

final readonly class JsonDomainEventDeserializer implements DomainEventDeserializer
{
    /** @param class-string<DomainEvent> $class */
    public function deserialize(string $domainEvent, string $class): DomainEvent
    {
        $event = SharedSerde::instance()->deserialize($domainEvent, from: 'json', to: $class);

        if (!$event instanceof DomainEvent) {
             throw new \RuntimeException('Deserialized object is not a domain event');
        }

        return $event;
    }
}
