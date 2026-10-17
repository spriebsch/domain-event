<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

final readonly class JsonDomainEventSerializer implements DomainEventSerializer
{
    public function serialize(DomainEvent $event): string
    {
        return json_encode($event, JSON_THROW_ON_ERROR);
    }
}
