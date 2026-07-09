<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use Crell\Serde\SerdeCommon;

final readonly class JsonDomainEventSerializer implements DomainEventSerializer
{
    public function serialize(DomainEvent $event): string
    {
        return new SerdeCommon()->serialize($event, format: 'json');
    }
}
