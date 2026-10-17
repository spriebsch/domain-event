<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

interface TestEvent extends DomainEvent
{
    public function id(): TestId;
}
