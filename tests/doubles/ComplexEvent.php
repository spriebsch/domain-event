<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

#[MapToTopic('spriebsch.domainEvent.test.complex')]
final readonly class ComplexEvent implements TestEvent
{
    /**
     * @param array<mixed, mixed> $array
     */
    public function __construct(
        private TestId          $id,
        private bool            $bool,
        private string          $string,
        private int             $int,
        private float           $float,
        private array           $array,
        private SomeEnum        $enum,
        private SomeBackedEnum  $backedEnum,
        private SomeValueObject $valueObject,
        private Nullable        $nullableTest,
        private SomeInterface   $someInterface,
        private SomeInterface   $secondInterfaceImplementation,
    ) {}

    #[UseAsCorrelationId]
    public function id(): TestId
    {
        return $this->id;
    }

    /**
     * @return array<int, string>
     */
    public function array(): array
    {
        // Ensure keys are sequential ints and values are strings for static analysis
        $mapped = array_map(static function ($v): string {
            if (is_string($v) || is_numeric($v) || (is_object($v) && method_exists($v, '__toString'))) {
                return (string) $v;
            }
            return 'non-stringable value';
        }, $this->array);

        return array_values($mapped);
    }

    public function valueObject(): SomeValueObject
    {
        return $this->valueObject;
    }

    public function bool(): bool
    {
        return $this->bool;
    }

    public function string(): string
    {
        return $this->string;
    }

    public function int(): int
    {
        return $this->int;
    }

    public function float(): float
    {
        return $this->float;
    }

    public function enum(): SomeEnum
    {
        return $this->enum;
    }

    public function backedEnum(): SomeBackedEnum
    {
        return $this->backedEnum;
    }

    public function nullableTest(): Nullable
    {
        return $this->nullableTest;
    }

    public function someInterface(): SomeInterface
    {
        return $this->someInterface;
    }

    public function secondInterfaceImplementation(): SomeInterface
    {
        return $this->secondInterfaceImplementation;
    }
}
