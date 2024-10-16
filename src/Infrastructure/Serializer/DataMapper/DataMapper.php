<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\DataMapper;

use App\Infrastructure\Serializer\Exceptions\SerializerException;

interface DataMapper
{
    /**
     * @template T of object
     *
     * @param array<string, mixed> $data
     * @param class-string<T> $to
     *
     * @return T
     *
     * @throws SerializerException
     */
    public function transform(array $data, string $to): object;
}
