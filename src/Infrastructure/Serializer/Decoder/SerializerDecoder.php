<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\Decoder;

use App\Infrastructure\Serializer\Exceptions\SerializerException;
use App\Infrastructure\Serializer\SerializerFormat;

interface SerializerDecoder
{
    public function supports(SerializerFormat $format): bool;

    /**
     * @param non-empty-string $payload
     * @return array<string, mixed>
     *
     * @throws SerializerException
     */
    public function decode(string $payload): array;
}
