<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(TopicMap::class)]
#[UsesClass(SimpleEvent::class)]
final class TopicMapTest extends TestCase
{
    private string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'topicmap');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function test_fromFile_throws_exception_if_not_a_file(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File or directory "/non/existent/file" does not exist');

        TopicMap::fromFile('/non/existent/file');
    }

    public function test_fromFile_throws_exception_if_file_does_not_return_array(): void
    {
        file_put_contents($this->tempFile, '<?php return "not-an-array";');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Topic map file must return array');

        TopicMap::fromFile($this->tempFile);
    }

    public function test_topicFor_throws_exception_if_no_topic_found(): void
    {
        $topicMap = TopicMap::fromArray([]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No topic found for class spriebsch\DomainEvent\SimpleEvent');

        $topicMap->topicFor(new SimpleEvent());
    }

    public function test_fromArray_and_classFor(): void
    {
        $topicMap = TopicMap::fromArray(['topic' => 'class']);

        $this->assertSame('class', $topicMap->classFor('topic'));
    }

    public function test_topicFor_returns_topic(): void
    {
        $topicMap = TopicMap::fromArray(['topic' => SimpleEvent::class]);

        $this->assertSame('topic', $topicMap->topicFor(new SimpleEvent()));
    }
}
