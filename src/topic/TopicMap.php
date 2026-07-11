<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use RuntimeException;
use spriebsch\filesystem\Filesystem;
use spriebsch\filesystem\File as FsFile;

final readonly class TopicMap
{
    public static function fromFile(string $path): self
    {
        $fs = Filesystem::from($path);
        /** @var FsFile $fs */
        $data = $fs->require();
        if (!is_array($data)) {
            throw new RuntimeException('Topic map file must return array');
        }

        $topicMap = [];
        foreach ($data as $topic => $class) {
            if (!is_string($topic) || !is_string($class)) {
                throw new RuntimeException('Topic map array must be array<string, string>');
            }
            $topicMap[$topic] = $class;
        }

        return self::fromArray($topicMap);
    }

    /** @param array<string, string> $topicMap */
    public static function fromArray(array $topicMap): self
    {
        return new self($topicMap);
    }

    /** @param array<string, string> $topicMap */
    private function __construct(private array $topicMap) {}

    public function classFor(string $topic): string
    {
        return (string) $this->topicMap[$topic];
    }

    public function topicFor(DomainEvent $event): string
    {
        foreach ($this->topicMap as $topic => $class) {
            if ($class === $event::class) {
                return (string) $topic;
            }
        }
        throw new RuntimeException(sprintf('No topic found for class %s', $event::class));
    }
}
