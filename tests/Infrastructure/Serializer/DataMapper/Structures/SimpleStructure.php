<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Serializer\DataMapper\Structures;

final class SimpleStructure
{
    public function __construct(
        public readonly string $key
    ) {

    }
}
