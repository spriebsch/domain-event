<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

#[MapToTopic('spriebsch.domainEvent.test.complex')]
final readonly class ComplexEvent implements TestEvent
{
    use JsonDomainEventTrait;

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

    public function asArray(): array
    {
        $value = $this->valueObject;
        $nullable = $this->nullableTest;

        return [
            'id' => $this->id->asString(),
            'bool' => $this->bool,
            'string' => $this->string,
            'int' => $this->int,
            'float' => $this->float,
            'array' => $this->array,
            'enum' => $this->enum->name,
            'backedEnum' => $this->backedEnum->value,
            'valueObject' => [
                'bool' => $value->bool(),
                'string' => $value->string(),
                'int' => $value->int(),
                'float' => $value->float(),
                'array' => $value->array(),
                'enum' => $value->enum()->name,
                'backedEnum' => $value->backedEnum()->value,
                'nestedValueObject' => ['string' => $value->nestedValueObject()->string()],
            ],
            'nullableTest' => [
                'bool' => $nullable->bool(),
                'string' => $nullable->string(),
                'int' => $nullable->int(),
                'float' => $nullable->float(),
                'array' => $nullable->array(),
                'enum' => $nullable->enum()?->name,
                'backedEnum' => $nullable->backedEnum()?->value,
            ],
            'someInterface' => self::interfaceAsArray($this->someInterface),
            'secondInterfaceImplementation' => self::interfaceAsArray($this->secondInterfaceImplementation),
        ];
    }

    public static function fromJson(string $json): static
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Expected an object payload');
        }

        $value = $data['valueObject'];
        $nullable = $data['nullableTest'];

        return new static(
            TestId::from($data['id']),
            $data['bool'],
            $data['string'],
            $data['int'],
            $data['float'],
            $data['array'],
            constant(SomeEnum::class . '::' . $data['enum']),
            SomeBackedEnum::from($data['backedEnum']),
            new SomeValueObject(
                $value['bool'],
                $value['string'],
                $value['int'],
                $value['float'],
                $value['array'],
                constant(SomeEnum::class . '::' . $value['enum']),
                SomeBackedEnum::from($value['backedEnum']),
                new NestedValueObject($value['nestedValueObject']['string']),
            ),
            new Nullable(
                $nullable['bool'],
                $nullable['string'],
                $nullable['int'],
                $nullable['float'],
                $nullable['array'],
                $nullable['enum'] === null ? null : constant(SomeEnum::class . '::' . $nullable['enum']),
                $nullable['backedEnum'] === null ? null : SomeBackedEnum::from($nullable['backedEnum']),
            ),
            self::interfaceFromArray($data['someInterface']),
            self::interfaceFromArray($data['secondInterfaceImplementation']),
        );
    }

    private static function interfaceAsArray(SomeInterface $value): array
    {
        return match (true) {
            $value instanceof ImplementsInterface => ['type' => 'interface', 'value' => $value->value()],
            $value instanceof SecondInterfaceImplementation => ['type' => 'secondInterface', 'value' => $value->value()],
        };
    }

    private static function interfaceFromArray(array $data): SomeInterface
    {
        return match ($data['type']) {
            'interface' => new ImplementsInterface($data['value']),
            'secondInterface' => new SecondInterfaceImplementation($data['value']),
        };
    }

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
