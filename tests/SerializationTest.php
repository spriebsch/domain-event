<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use spriebsch\money\Amount;
use spriebsch\money\Currency;
use spriebsch\money\Money;

#[CoversClass(JsonDomainEventSerializer::class)]
#[CoversClass(JsonDomainEventDeserializer::class)]
#[UsesClass(AbstractId::class)]
class SerializationTest extends TestCase
{
    public function test_serde_serializes_and_deserializes_object(): void
    {
        $id = TestId::generate();
        $serializer = new JsonDomainEventSerializer();
        $deserializer = new JsonDomainEventDeserializer();

        $object = new ComplexEvent(
            $id,
            true,
            'the-string',
            42,
            3.14,
            ['a', 'b', 'c'],
            SomeEnum::A,
            SomeBackedEnum::B,
            new SomeValueObject(
                true,
                'the-string',
                42,
                3.14,
                ['a', 'b', 'c'],
                SomeEnum::A,
                SomeBackedEnum::B,
                new NestedValueObject('the-string')
            ),
            new Nullable(
                null,
                null,
                null,
                null,
                null,
                null,
                null,
            ),
            new ImplementsInterface('the-string'),
            new SecondInterfaceImplementation(42),
        );

        $jsonString = $serializer->serialize($object);

        $deserializedObject = $deserializer->deserialize($jsonString, ComplexEvent::class);

        $this->assertEquals($object, $deserializedObject);
    }
}
