<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

trait IdJsonEventTrait
{
    use JsonDomainEventTrait;

    abstract public function id(): TestId;

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

        return new static(TestId::from($data['id']));
    }
}
