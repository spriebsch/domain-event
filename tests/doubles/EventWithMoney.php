<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use spriebsch\money\Money;

#[MapToTopic('spriebsch.domainEvent.test.money')]
final readonly class EventWithMoney implements DomainEvent
{
    public function __construct(
        private TestId $id,
        private Money $money
    ) {}

    public static function from(TestId $id, Money $money): self
    {
        return new self($id, $money);
    }

    #[UseAsCorrelationId]
    public function id(): TestId
    {
        return $this->id;
    }

    public function money(): Money
    {
        return $this->money;
    }
}
