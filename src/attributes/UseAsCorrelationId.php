<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class UseAsCorrelationId
{
}
