<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;

trait CanApplyDomainEventsTrait
{
    private function apply(DomainEvent $event): void
    {
        $method = $this->applyMethodFor($event);

        $this->ensureMethodExists($method, $event);
        $this->ensureExpectedSignature($method, $event);
        $this->ensureVoidReturnType($method, $event);

        $this->{$method}($event);
    }

    private function applyMethodFor(DomainEvent $event): string
    {
        return 'apply' . array_slice(explode('\\', $event::class), -1)[0];
    }

    private function ensureMethodExists(string $method, DomainEvent $event): void
    {
        if (!method_exists($this, $method)) {
            throw new RuntimeException(
                sprintf(
                    'No method %s',
                    $this->expectedMethodNameAndSignature($method, $event)
                )
            );
        }
    }

    private function ensureExpectedSignature(string $method, DomainEvent $event): void
    {
        $reflectionMethod = new ReflectionMethod($this::class, $method);

        $parameters = $reflectionMethod->getParameters();

        if (count($parameters) === 0) {
            throw new RuntimeException(
                sprintf(
                    'Method %s() has no parameters, expected %s',
                    $method,
                    $this->expectedMethodNameAndSignature($method, $event)
                )
            );
        }

        if (count($parameters) > 1) {
            throw new RuntimeException(
                sprintf(
                    'Method %s(...) has too many parameters, expected %s',
                    $method,
                    $this->expectedMethodNameAndSignature($method, $event)
                )
            );
        }

        $parameter = $parameters[0];

        if (!$parameter->hasType()) {
            throw new RuntimeException(
                sprintf(
                    'Method %s($%s) parameter has no type, expected %s',
                    $method,
                    $parameter->getName(),
                    $this->expectedMethodNameAndSignature($method, $event)
                )
            );
        }

        $type = $parameter->getType();
        if (!$type instanceof ReflectionNamedType) {
            throw new RuntimeException(
                sprintf(
                    'Method %s($%s) parameter must be named type %s',
                    $method,
                    $parameter->getName(),
                    $this->expectedMethodNameAndSignature($method, $event)
                )
            );
        }

        if ($type->getName() !== $event::class) {
            throw new RuntimeException(
                sprintf(
                    'Method %s(%s $%s) parameter type must be %s',
                    $method,
                    $type->getName(),
                    $parameter->getName(),
                    $this->expectedMethodNameAndSignature($method, $event)
                )
            );
        }
    }


    private function ensureVoidReturnType(string $method, DomainEvent $event): void
    {
        $reflectionMethod = new ReflectionMethod($this::class, $method);
        $returnType = $reflectionMethod->getReturnType();

        if (!$returnType instanceof ReflectionNamedType || $returnType->getName() !== 'void') {
            throw new RuntimeException(
                sprintf(
                    'Method %s::%s() must have void return type',
                    $this::class,
                    $method
                )
            );
        }
    }

    private function expectedMethodNameAndSignature(string $method, DomainEvent $event): string
    {
        return sprintf('%s::%s(%s $event): void', $this::class, $method, $event::class);
    }
}
