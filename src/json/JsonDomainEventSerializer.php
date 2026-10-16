<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

final readonly class JsonDomainEventSerializer implements DomainEventSerializer
{
    public function serialize(DomainEvent $event): string
    {
        $json = SharedSerde::instance()->serialize($event, format: 'json');
        assert(is_string($json));
        return $json;
    }
}
