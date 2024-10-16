<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\DataMapper;

use App\Infrastructure\Serializer\Exceptions\SerializerException;

final class SimpleDataMapper implements DataMapper
{
    public function transform(array $data, string $to): object
    {
        try {
            $reflectionClass = new \ReflectionClass($to);
            $constructor = $reflectionClass->getConstructor();

            if ($constructor === null) {
                throw new SerializerException(
                    sprintf('Class `%s` does not have a constructor', $to)
                );
            }

            $constructorParameters = $constructor->getParameters();
            $constructorArgs = [];

            foreach ($constructorParameters as $parameter) {
                $paramName = $this->toSnakeCase($parameter->getName());

                if (array_key_exists($paramName, $data)) {
                    /** @var string|int|float|array<array-key, mixed> $value */
                    $value = $data[$paramName];
                    $expectedType = $parameter->getType();

                    if ($expectedType !== null) {
                        $value = $this->castValue($value, $expectedType);
                    }

                    $constructorArgs[] = $value;
                } elseif ($parameter->isOptional()) {
                    $constructorArgs[] = $parameter->getDefaultValue();
                } else {
                    throw new SerializerException(
                        sprintf(
                            'Missing required constructor argument `%s` for class `%s`',
                            $paramName,
                            $to
                        )
                    );
                }
            }

            return $reflectionClass->newInstanceArgs($constructorArgs);
        } catch (\ReflectionException $exception) {
            throw new SerializerException(
                sprintf('Unable to create instance of `%s`: %s', $to, $exception->getMessage())
            );
        }
    }

    /**
     * @param string $input
     * @return string
     */
    private function toSnakeCase(string $input): string
    {
        return strtolower((string)preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }

    /**
     * @param string|int|float|array<array-key, mixed>|bool|null $value
     * @param \ReflectionType $expectedType
     * @return mixed
     *
     * @throws SerializerException
     */
    private function castValue(string|int|float|array|bool|null $value, \ReflectionType $expectedType): mixed
    {
        if ($expectedType instanceof \ReflectionNamedType) {
            $typeName = $expectedType->getName();

            return match ($typeName) {
                'int' => (int)$value,
                'float' => (float)$value,
                'bool' => (bool)$value,
                'string' => $value,
                'DateTimeImmutable' => is_string($value) ? new \DateTimeImmutable($value) : null,
                default => class_exists($typeName) && is_array($value)
                    ? $this->transform($value, $typeName)
                    : throw new SerializerException(sprintf('Unsupported type `%s`.', $typeName)),
            };
        }

        throw new SerializerException('Implemented only named types');
    }
}
