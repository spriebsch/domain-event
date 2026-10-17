<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

#[MapToTopic('spriebsch.domainEvent.test.simple')]
final class SimpleEvent implements TestEvent
{
    use JsonDomainEventTrait;

    private TestId $id;

    public function asArray(): array
    {
        return ['id' => $this->id()->asString()];
    }

    public static function fromJson(string $json): static
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data) || !isset($data['id']) || !is_string($data['id'])) {
            throw new \InvalidArgumentException('Expected a string id');
        }

        $event = new static();
        $event->id = TestId::from($data['id']);

        return $event;
    }

    #[UseAsCorrelationId]
    public function id(): TestId
    {
        if (!isset($this->id)) {
            $this->id = TestId::generate();
        }

        return $this->id;
    }
}
