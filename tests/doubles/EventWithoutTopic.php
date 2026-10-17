<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

// Intentionally no MapToTopic attribute
final readonly class EventWithoutTopic implements DomainEvent
{
    use EmptyJsonEventTrait;
}
