<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchemaVersion::class)]
final class SchemaVersionTest extends TestCase
{
    public function test_from_creates_schema_version(): void
    {
        $version = SchemaVersion::from(1);
        $this->assertSame(1, $version->asInt());
    }

    public function test_equals_returns_true_when_values_are_equal(): void
    {
        $version1 = SchemaVersion::from(1);
        $version2 = SchemaVersion::from(1);
        $this->assertTrue($version1->equals($version2));
    }

    public function test_equals_returns_false_when_values_are_different(): void
    {
        $version1 = SchemaVersion::from(1);
        $version2 = SchemaVersion::from(2);
        $this->assertFalse($version1->equals($version2));
    }
}
