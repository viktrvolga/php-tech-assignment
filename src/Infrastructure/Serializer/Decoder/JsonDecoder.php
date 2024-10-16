<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\Decoder;

use App\Infrastructure\Serializer\Exceptions\SerializerException;
use App\Infrastructure\Serializer\SerializerFormat;

final readonly class JsonDecoder implements SerializerDecoder
{
    public function supports(SerializerFormat $format): bool
    {
        return $format === SerializerFormat::JSON;
    }

    public function decode(string $payload): array
    {
        if (json_validate($payload)) {
            /** @var array<string, mixed> $result */
            $result = json_decode($payload, true);

            return $result;
        }

        throw new SerializerException('The passed json is invalid');
    }
}
