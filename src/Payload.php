<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

final readonly class Payload
{
    public function __construct(
        private Envelope    $envelope,
        private DomainEvent $event
    ) {}

    public function envelope(): Envelope
    {
        return $this->envelope;
    }

    public function event(): DomainEvent
    {
        return $this->event;
    }
}
