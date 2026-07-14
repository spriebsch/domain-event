<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use Crell\Serde\SerdeCommon;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use spriebsch\money\Amount;
use spriebsch\money\Currency;
use spriebsch\money\Fraction;
use spriebsch\money\Money;

#[CoversNothing]
class SerializationTest extends TestCase
{
    public function test_serde_serializes_and_deserializes_object(): void
    {
        $id = TestId::generate();
        $serde = new SerdeCommon();

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
            )
        );

        $jsonString = $serde->serialize($object, format: 'json');
        $deserializedObject = $serde->deserialize($jsonString, from: 'json', to: ComplexEvent::class);
        $this->assertEquals($object, $deserializedObject);
    }

    public function test_serde_serializes_and_deserializes_object_with_money(): void
    {
        $id = TestId::generate();
        $serde = new SerdeCommon();

        $object = new EventWithMoney(
            $id,
            Money::from(Amount::cents(100), TestSupportedCurrencies::EUR)
        );
        
        $jsonString = $serde->serialize($object, format: 'json');
        $deserializedObject = $serde->deserialize($jsonString, from: 'json', to: EventWithMoney::class);
        $this->assertEquals($object, $deserializedObject);
    }
}
